<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SesionController extends Controller
{
    /**
     * Vista principal del scanner QR (para operadores)
     */
    public function scanner()
    {
        $sesionesActivas = Sesion::activas()
                                ->with('alumno')
                                ->orderBy('hora_inicio', 'desc')
                                ->get();

        return view('sesiones.scanner', compact('sesionesActivas'));
    }

    /**
     * Procesar código QR escaneado
     */
    public function procesarQR(Request $request)
    {
        $request->validate([
            'npi' => 'required|string',
            'actividad' => 'required|string|max:500'
        ]);

        try {
            // Limpiar NPI (quitar guiones y espacios)
            $npiLimpio = str_replace(['-', ' '], '', $request->npi);
            
            // Buscar alumno por NPI (con o sin guión)
            $alumno = Alumno::where(function($query) use ($request, $npiLimpio) {
                $query->where('npi', $request->npi)
                    ->orWhere('npi', $npiLimpio)
                    ->orWhereRaw("REPLACE(npi, '-', '') = ?", [$npiLimpio]);
            })
            ->where('is_active', true)
            ->first();
            
            if (!$alumno) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alumno no encontrado o inactivo con NPI: ' . $request->npi . '. Verifica que el NPI sea correcto (formato: 1234567-8)'
                ]);
            }

            DB::beginTransaction();
            
            try {
                // Verificar si ya tiene una sesión activa CON BLOQUEO
                $sesionActiva = Sesion::where('alumno_id', $alumno->id)
                                    ->where('estado', 'activa')
                                    ->lockForUpdate()
                                    ->first();

                if ($sesionActiva) {
                    // FINALIZAR sesión existente
                    $sesionActiva->update([
                        'hora_fin' => now(),
                        'estado' => 'finalizada',
                        'usuario_fin_id' => Auth::id(),
                        'duracion_minutos' => $sesionActiva->hora_inicio->diffInMinutes(now())
                    ]);

                    // --- TELEMETRÍA: PARAR GRABACIÓN ---
                    $pathFlags = storage_path('app/flags');
                    if (!file_exists($pathFlags)) mkdir($pathFlags, 0777, true);
                    file_put_contents($pathFlags . '/stop_' . $sesionActiva->id . '.txt', 'STOP');
                    
                    // Actualizamos nombre del archivo en BD
                    $sesionActiva->archivo_vuelo = "vuelo_sesion_" . $sesionActiva->id . ".json";
                    $sesionActiva->save();
                    // -----------------------------------
                    
                    DB::commit();
                    
                    return response()->json([
                        'success' => true,
                        'action' => 'finalizar',
                        'message' => 'Sesión finalizada correctamente',
                        'alumno' => $alumno->nombre_completo,
                        'duracion' => $sesionActiva->duracion_minutos . ' minutos',
                        'hora_inicio' => $sesionActiva->hora_inicio->format('H:i'),
                        'hora_fin' => $sesionActiva->hora_fin->format('H:i')
                    ]);
                    
                } else {
                    // DOBLE VERIFICACIÓN antes de crear nueva sesión
                    $verificacionExtra = Sesion::where('alumno_id', $alumno->id)
                                            ->where('estado', 'activa')
                                            ->exists();
                    
                    if ($verificacionExtra) {
                        DB::rollback();
                        return response()->json([
                            'success' => false,
                            'message' => 'El alumno ya tiene una sesión activa. Intenta de nuevo.'
                        ]);
                    }
                    
                    // INICIAR nueva sesión
                    $sesion = Sesion::create([
                        'alumno_id' => $alumno->id,
                        'npi' => $request->npi,
                        'fecha' => today(),
                        'hora_inicio' => now(),
                        'actividad' => $request->actividad,
                        'estado' => 'activa',
                        'usuario_inicio_id' => Auth::id()
                    ]);

                    // --- TELEMETRÍA: INICIAR GRABACIÓN ---
                    // Asegúrate que esta ruta coincida con donde guardaste el script
                    $scriptPath = base_path('registro_simulador/pruebas_telemetria/receptor.py');
                    // Comando Windows para background (start /B)
                    $comando = "start /B python \"$scriptPath\" " . $sesion->id;
                    pclose(popen($comando, "r"));
                    // -------------------------------------

                    DB::commit();
                    
                    return response()->json([
                        'success' => true,
                        'action' => 'iniciar',
                        'message' => 'Sesión iniciada correctamente',
                        'alumno' => $alumno->nombre_completo,
                        'hora_inicio' => $sesion->hora_inicio->format('H:i'),
                        'npi' => $alumno->npi
                    ]);
                }
                
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error del sistema: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listado de todas las sesiones (solo admin)
     */
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('sesiones.scanner')
                           ->with('error', 'No tienes permisos para ver el historial completo');
        }

        $query = Sesion::with(['alumno', 'usuarioInicio', 'usuarioFin']);

        // Aplicar filtros
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }
        
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
        }

        if ($request->filled('alumno_buscar')) {
            $query->whereHas('alumno', function($q) use ($request) {
                $q->where('nombre_completo', 'like', '%' . $request->alumno_buscar . '%')
                  ->orWhere('npi', 'like', '%' . $request->alumno_buscar . '%');
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $sesiones = $query->orderBy('fecha', 'desc')
                          ->orderBy('hora_inicio', 'desc')
                          ->paginate(20)
                          ->withQueryString();
        
        return view('sesiones.index', compact('sesiones'));
    }

    /**
     * Ver sesiones activas (admin y operadores)
     */
    public function activas()
    {
        $sesionesActivas = Sesion::activas()
                                ->with(['alumno', 'usuarioInicio'])
                                ->orderBy('hora_inicio', 'asc')
                                ->get();

        return view('sesiones.activas', compact('sesionesActivas'));
    }

    /**
     * Finalizar sesión directamente (sin QR)
     */
    public function finalizarSesionDirecta($id)
    {
        try {
            DB::beginTransaction();
            
            $sesion = Sesion::where('id', $id)
                           ->where('estado', 'activa')
                           ->lockForUpdate()
                           ->first();
            
            if (!$sesion) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'La sesión no existe o ya está finalizada'
                ]);
            }

            $sesion->update([
                'hora_fin' => now(),
                'estado' => 'finalizada',
                'usuario_fin_id' => Auth::id(),
                'duracion_minutos' => $sesion->hora_inicio->diffInMinutes(now())
            ]);

            // --- TELEMETRÍA: PARAR GRABACIÓN ---
            $pathFlags = storage_path('app/flags');
            if (!file_exists($pathFlags)) mkdir($pathFlags, 0777, true);
            file_put_contents($pathFlags . '/stop_' . $sesion->id . '.txt', 'STOP');
            
            $sesion->archivo_vuelo = "vuelo_sesion_" . $sesion->id . ".json";
            $sesion->save();
            // -----------------------------------
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Sesión finalizada correctamente',
                'alumno' => $sesion->alumno->nombre_completo,
                'duracion' => $sesion->duracion_minutos . ' minutos',
                'hora_inicio' => $sesion->hora_inicio->format('H:i'),
                'hora_fin' => $sesion->hora_fin->format('H:i')
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al finalizar sesión: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX: Obtener sesiones activas para actualización en tiempo real
     */
    public function activasAjax()
    {
        $sesionesActivas = Sesion::activas()
                                ->with('alumno')
                                ->orderBy('hora_inicio', 'asc')
                                ->get();

        $sesionesFormatted = $sesionesActivas->map(function ($sesion) {
            return [
                'id' => $sesion->id,
                'alumno' => [
                    'nombre_completo' => $sesion->alumno->nombre_completo,
                    'npi' => $sesion->alumno->npi
                ],
                'hora_inicio' => $sesion->hora_inicio->format('H:i'),
                'tiempo_transcurrido' => $sesion->tiempo_transcurrido,
                'actividad' => $sesion->actividad,
                'necesita_atencion' => $sesion->necesitaAtencion()
            ];
        });

        return response()->json([
            'count' => $sesionesActivas->count(),
            'sesiones' => $sesionesFormatted
        ]);
    }

    /**
     * Finalizar sesión manualmente (solo admin)
     */
    public function finalizarManual($id)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'No tienes permisos para esta acción');
        }

        try {
            $sesion = Sesion::findOrFail($id);
            
            if ($sesion->estado !== 'activa') {
                return redirect()->back()->with('error', 'La sesión ya está finalizada');
            }

            $sesion->update([
                'hora_fin' => now(),
                'estado' => 'finalizada',
                'usuario_fin_id' => Auth::id(),
                'duracion_minutos' => $sesion->hora_inicio->diffInMinutes(now())
            ]);

            // --- TELEMETRÍA: PARAR GRABACIÓN ---
            $pathFlags = storage_path('app/flags');
            if (!file_exists($pathFlags)) mkdir($pathFlags, 0777, true);
            file_put_contents($pathFlags . '/stop_' . $sesion->id . '.txt', 'STOP');
            
            $sesion->archivo_vuelo = "vuelo_sesion_" . $sesion->id . ".json";
            $sesion->save();
            // -----------------------------------
            
            return redirect()->back()->with('success', 'Sesión finalizada manualmente');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al finalizar sesión: ' . $e->getMessage());
        }
    }

    /**
     * Reporte diario
     */
    public function reporteDiario(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('sesiones.scanner')
                           ->with('error', 'No tienes permisos para ver reportes');
        }

        $fecha = $request->get('fecha', today());
        $sesiones = Sesion::whereDate('fecha', $fecha)
                         ->with(['alumno', 'usuarioInicio', 'usuarioFin'])
                         ->orderBy('hora_inicio', 'desc')
                         ->get();

        $estadisticas = [
            'fecha' => Carbon::parse($fecha),
            'total_sesiones' => $sesiones->count(),
            'sesiones_finalizadas' => $sesiones->where('estado', 'finalizada')->count(),
            'sesiones_activas' => $sesiones->where('estado', 'activa')->count(),
            'tiempo_total_minutos' => $sesiones->where('estado', 'finalizada')->sum('duracion_minutos'),
            'promedio_duracion' => $sesiones->where('estado', 'finalizada')->avg('duracion_minutos'),
            'alumnos_unicos' => $sesiones->unique('alumno_id')->count()
        ];

        return view('sesiones.reporte-diario', compact('sesiones', 'estadisticas'));
    }

    /**
     * Mostrar formulario de edición (NUEVO)
     */
    public function edit($id)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'No tienes permisos para esta acción');
        }

        $sesion = Sesion::with(['alumno', 'usuarioInicio', 'usuarioFin'])->findOrFail($id);
        
        return view('sesiones.edit', compact('sesion'));
    }

    /**
     * Actualizar sesión (NUEVO)
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'No tienes permisos para esta acción');
        }

        $sesion = Sesion::findOrFail($id);

        $validated = $request->validate([
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i',
            'actividad' => 'required|string|max:500',
            'estado' => 'required|in:activa,finalizada,cancelada',
            'observaciones' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Construir datetime completo
            $fechaInicio = Carbon::parse($validated['fecha'] . ' ' . $validated['hora_inicio']);
            $fechaFin = null;
            $duracionMinutos = null;

            if ($validated['hora_fin']) {
                $fechaFin = Carbon::parse($validated['fecha'] . ' ' . $validated['hora_fin']);
                
                // Validar que hora_fin sea posterior a hora_inicio
                if ($fechaFin->lte($fechaInicio)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'La hora de fin debe ser posterior a la hora de inicio');
                }
                
                $duracionMinutos = $fechaInicio->diffInMinutes($fechaFin);
            }

            // Si el estado cambia a finalizada, asegurar que tenga hora_fin
            if ($validated['estado'] === 'finalizada' && !$fechaFin) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Las sesiones finalizadas deben tener hora de fin');
            }

            // Si el estado cambia a activa, remover hora_fin
            if ($validated['estado'] === 'activa') {
                $fechaFin = null;
                $duracionMinutos = null;
            }

            $sesion->update([
                'fecha' => $validated['fecha'],
                'hora_inicio' => $fechaInicio,
                'hora_fin' => $fechaFin,
                'duracion_minutos' => $duracionMinutos,
                'actividad' => $validated['actividad'],
                'estado' => $validated['estado'],
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('sesiones.index')
                ->with('success', 'Sesión actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar sesión: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar sesión (ACTUALIZADO - Responde JSON para AJAX)
     */
    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para esta acción'
            ], 403);
        }

        try {
            $sesion = Sesion::findOrFail($id);
            
            // Guardar info antes de eliminar para el mensaje
            $alumnoNombre = $sesion->alumno->nombre_completo;
            $fecha = $sesion->fecha->format('d/m/Y');
            
            $sesion->delete();

            return response()->json([
                'success' => true,
                'message' => "Sesión de {$alumnoNombre} del {$fecha} eliminada correctamente"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar sesión: ' . $e->getMessage()
            ], 500);
        }
    }
}