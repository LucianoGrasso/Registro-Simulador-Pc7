<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Importante para registrar errores silenciosos

class SesionController extends Controller
{
    // ... (Método scanner queda igual) ...
    public function scanner()
    {
        $sesionesActivas = Sesion::activas()
                                ->with('alumno')
                                ->orderBy('hora_inicio', 'desc')
                                ->get();

        return view('sesiones.scanner', compact('sesionesActivas'));
    }

    /**
     * Procesar código QR escaneado (AQUÍ ESTÁ LA LÓGICA DE INICIO Y FIN POR QR)
     */
    public function procesarQR(Request $request)
    {
        $request->validate([
            'npi' => 'required|string',
            'actividad' => 'required|string|max:500'
        ]);

        try {
            $npiLimpio = str_replace(['-', ' '], '', $request->npi);
            
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
                    'message' => 'Alumno no encontrado o inactivo con NPI: ' . $request->npi
                ]);
            }

            DB::beginTransaction();
            
            try {
                // Verificar si ya tiene una sesión activa
                $sesionActiva = Sesion::where('alumno_id', $alumno->id)
                                    ->where('estado', 'activa')
                                    ->lockForUpdate()
                                    ->first();

                if ($sesionActiva) {
                    // === FINALIZAR POR QR ===
                    $sesionActiva->update([
                        'hora_fin' => now(),
                        'estado' => 'finalizada',
                        'usuario_fin_id' => Auth::id(),
                        'duracion_minutos' => $sesionActiva->hora_inicio->diffInMinutes(now())
                    ]);
                    
                    // [TELEMETRÍA] DETENER GRABACIÓN (Protegido contra fallos)
                    try {
                        $this->detenerTelemetria($sesionActiva);
                    } catch (\Exception $eTel) {
                        Log::error("Fallo al detener telemetria en QR: " . $eTel->getMessage());
                    }
                    
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
                    // === INICIAR POR QR ===
                    $verificacionExtra = Sesion::where('alumno_id', $alumno->id)
                                            ->where('estado', 'activa')
                                            ->exists();
                    
                    if ($verificacionExtra) {
                        DB::rollback();
                        return response()->json([
                            'success' => false,
                            'message' => 'El alumno ya tiene una sesión activa.'
                        ]);
                    }
                    
                    $sesion = Sesion::create([
                        'alumno_id' => $alumno->id,
                        'npi' => $request->npi,
                        'fecha' => today(),
                        'hora_inicio' => now(),
                        'actividad' => $request->actividad,
                        'estado' => 'activa',
                        'usuario_inicio_id' => Auth::id()
                    ]);

                    // [TELEMETRÍA] INICIAR GRABACIÓN (Protegido contra fallos)
                    try {
                        $this->iniciarTelemetria($sesion->id);
                    } catch (\Exception $eTel) {
                        Log::error("Fallo al iniciar telemetria en QR: " . $eTel->getMessage());
                    }

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

    // ... (Métodos index y activas quedan igual) ...
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('sesiones.scanner')->with('error', 'Sin permisos');
        }
        $query = Sesion::with(['alumno', 'usuarioInicio', 'usuarioFin']);
        
        if ($request->filled('fecha')) $query->whereDate('fecha', $request->fecha);
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
        if ($request->filled('alumno_buscar')) {
            $query->whereHas('alumno', function($q) use ($request) {
                $q->where('nombre_completo', 'like', '%' . $request->alumno_buscar . '%')
                  ->orWhere('npi', 'like', '%' . $request->alumno_buscar . '%');
            });
        }
        if ($request->filled('estado')) $query->where('estado', $request->estado);

        $sesiones = $query->orderBy('fecha', 'desc')->orderBy('hora_inicio', 'desc')->paginate(20)->withQueryString();
        return view('sesiones.index', compact('sesiones'));
    }

    public function activas()
    {
        $sesionesActivas = Sesion::activas()->with(['alumno', 'usuarioInicio'])->orderBy('hora_inicio', 'asc')->get();
        return view('sesiones.activas', compact('sesionesActivas'));
    }

    /**
     * Finalizar sesión directamente (Botón en dashboard)
     */
    public function finalizarSesionDirecta($id)
    {
        try {
            DB::beginTransaction();
            
            $sesion = Sesion::where('id', $id)->where('estado', 'activa')->lockForUpdate()->first();
            
            if (!$sesion) {
                DB::rollback();
                return response()->json(['success' => false, 'message' => 'Sesión no encontrada']);
            }

            $sesion->update([
                'hora_fin' => now(),
                'estado' => 'finalizada',
                'usuario_fin_id' => Auth::id(),
                'duracion_minutos' => $sesion->hora_inicio->diffInMinutes(now())
            ]);
            
            // [TELEMETRÍA] DETENER GRABACIÓN (Protegido)
            try {
                $this->detenerTelemetria($sesion);
            } catch (\Exception $eTel) {
                Log::error("Fallo al detener telemetria (Directa): " . $eTel->getMessage());
            }
            
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
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // ... (activasAjax igual) ...
    public function activasAjax()
    {
        $sesionesActivas = Sesion::activas()->with('alumno')->orderBy('hora_inicio', 'asc')->get();
        $sesionesFormatted = $sesionesActivas->map(function ($sesion) {
            return [
                'id' => $sesion->id,
                'alumno' => ['nombre_completo' => $sesion->alumno->nombre_completo, 'npi' => $sesion->alumno->npi],
                'hora_inicio' => $sesion->hora_inicio->format('H:i'),
                'tiempo_transcurrido' => $sesion->tiempo_transcurrido,
                'actividad' => $sesion->actividad,
                'necesita_atencion' => $sesion->necesitaAtencion()
            ];
        });
        return response()->json(['count' => $sesionesActivas->count(), 'sesiones' => $sesionesFormatted]);
    }

    /**
     * Finalizar sesión manualmente (Botón en historial Admin)
     */
    public function finalizarManual($id)
    {
        if (!Auth::user()->isAdmin()) return redirect()->back()->with('error', 'Sin permisos');

        try {
            $sesion = Sesion::findOrFail($id);
            
            if ($sesion->estado !== 'activa') return redirect()->back()->with('error', 'Ya finalizada');

            $sesion->update([
                'hora_fin' => now(),
                'estado' => 'finalizada',
                'usuario_fin_id' => Auth::id(),
                'duracion_minutos' => $sesion->hora_inicio->diffInMinutes(now())
            ]);

            // [TELEMETRÍA] DETENER GRABACIÓN (Protegido)
            try {
                $this->detenerTelemetria($sesion);
            } catch (\Exception $eTel) {
                Log::error("Fallo al detener telemetria (Manual): " . $eTel->getMessage());
            }
            
            return redirect()->back()->with('success', 'Sesión finalizada manualmente');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ... (Resto de métodos: reporteDiario, edit, update, destroy quedan igual) ...
    public function reporteDiario(Request $request) { /* ... tu código ... */ return view('sesiones.reporte-diario'); } // Simplificado aquí para no alargar
    public function edit($id) { /* ... tu código ... */ return view('sesiones.edit'); }
    public function update(Request $request, $id) { /* ... tu código ... */ return redirect()->route('sesiones.index'); }
    public function destroy($id) { /* ... tu código ... */ return response()->json(['success'=>true]); }


    // ==========================================
    // FUNCIONES PRIVADAS DE TELEMETRÍA (NUEVO)
    // ==========================================

    /**
     * Lanza el script de Python en segundo plano
     */
    private function iniciarTelemetria($sesionId)
    {
        // 1. PEGA AQUÍ LA SEGUNDA RUTA QUE COPIASTE DEL CMD
        // IMPORTANTE: Usa DOBLE barra invertida (\\) en lugar de una sola (\)
        $pythonExe = "C:\\Users\\Pc-cockpit\\AppData\\Local\\Python\\bin\\python.exe"; 

        // 2. Ruta del script (Laravel la detecta sola)
        $scriptPath = base_path('pruebas_telemetria/receptor.py');
        
        // 3. Comando explícito: Le decimos a Windows "Usa ESTE python, no el otro"
        // start /B ejecuta en segundo plano
        $comando = "start /B \"\" \"$pythonExe\" \"$scriptPath\" " . $sesionId;
        
        // Ejecutar
        pclose(popen($comando, "r"));
        
        Log::info("Telemetría iniciada con ruta explícita: $comando");
    }

    /**
     * Crea el archivo bandera para que Python se detenga
     */
    private function detenerTelemetria($sesion)
    {
        $pathFlags = storage_path('app/flags');
        
        // Crear carpeta si no existe (esto evita el error 500)
        if (!file_exists($pathFlags)) {
            mkdir($pathFlags, 0777, true);
        }
        
        // Crear archivo STOP
        file_put_contents($pathFlags . '/stop_' . $sesion->id . '.txt', 'STOP');
        
        // Asignar el nombre del archivo JSON que Python va a crear
        $sesion->archivo_vuelo = "vuelo_sesion_" . $sesion->id . ".json";
        $sesion->save();
        
        Log::info("Señal de stop enviada para sesión: " . $sesion->id);
    }
}