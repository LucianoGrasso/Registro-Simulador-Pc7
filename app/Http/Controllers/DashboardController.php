<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

Carbon::setLocale('es');

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            return $this->dashboardAdmin();
        } else {
            return $this->dashboardOperador();
        }
    }

    private function dashboardOperador()
    {
        $sesionesHoyFinalizadas = Sesion::whereDate('fecha', today())
                                    ->where('estado', 'finalizada')
                                    ->get();

        $estadisticas = [
            'sesiones_hoy' => Sesion::whereDate('fecha', today())->count(),
            'sesiones_activas' => Sesion::where('estado', 'activa')->count(),
            'tiempo_total_hoy' => $this->calcularTiempoRealMaquina($sesionesHoyFinalizadas),
            'alumnos_hoy' => Sesion::whereDate('fecha', today())->distinct('alumno_id')->count(),
        ];

        $alumnosActivos = Alumno::withCount(['sesiones' => function($query) {
                                    $query->whereMonth('fecha', date('m'))
                                          ->whereYear('fecha', date('Y'));
                                }])
                                ->having('sesiones_count', '>', 0)
                                ->orderBy('sesiones_count', 'desc')
                                ->limit(5)
                                ->get();

        $sesionesActivas = Sesion::activas()
                                ->with(['alumno', 'usuarioInicio'])
                                ->orderBy('hora_inicio', 'asc')
                                ->get();

        $sesionesRecientes = Sesion::whereDate('fecha', today())
                                  ->with(['alumno', 'usuarioInicio', 'usuarioFin'])
                                  ->orderBy('hora_inicio', 'desc')
                                  ->limit(15)
                                  ->get();

        $estadisticasSemana = $this->getEstadisticasSemana();

        $sesionesAtencion = Sesion::activas()
                                 ->with('alumno')
                                 ->get()
                                 ->filter(function($sesion) {
                                     return $sesion->necesitaAtencion();
                                 });

        return view('dashboard.operador', compact(
            'estadisticas',
            'sesionesActivas',
            'sesionesRecientes',
            'estadisticasSemana',
            'sesionesAtencion',
            'alumnosActivos'
        ));
    }

    private function dashboardAdmin()
    {
        $sesionesHoyFinalizadas = Sesion::whereDate('fecha', today())
                                    ->where('estado', 'finalizada')
                                    ->get();

        $estadisticas = [
            'total_alumnos' => Alumno::where('is_active', true)->count(),
            'total_sesiones_hoy' => Sesion::whereDate('fecha', today())->count(),
            'sesiones_activas' => Sesion::where('estado', 'activa')->count(),
            'tiempo_total_hoy' => $this->calcularTiempoRealMaquina($sesionesHoyFinalizadas),
        ];

        $sesionesActivas = Sesion::activas()
                                ->with(['alumno', 'usuarioInicio'])
                                ->orderBy('hora_inicio', 'asc')
                                ->get();

        $sesionesRecientes = Sesion::with(['alumno', 'usuarioInicio', 'usuarioFin'])
                                  ->orderBy('created_at', 'desc')
                                  ->limit(10)
                                  ->get();

        $estadisticasSemana = $this->getEstadisticasSemana();

        $alumnosActivos = Alumno::withCount(['sesiones' => function($query) {
                                    $query->whereMonth('fecha', date('m'))
                                          ->whereYear('fecha', date('Y'));
                                }])
                                ->having('sesiones_count', '>', 0)
                                ->orderBy('sesiones_count', 'desc')
                                ->get();

        $sesionesAtencion = Sesion::activas()
                                 ->with('alumno')
                                 ->get()
                                 ->filter(function($sesion) {
                                     return $sesion->necesitaAtencion();
                                 });

        return view('dashboard.admin', compact(
            'estadisticas',
            'sesionesActivas',
            'sesionesRecientes',
            'estadisticasSemana',
            'alumnosActivos',
            'sesionesAtencion'
        ));
    }

    public function datosActualizados()
    {
        $sesionesHoyFinalizadas = Sesion::whereDate('fecha', today())
                                    ->where('estado', 'finalizada')
                                    ->get();

        $estadisticas = [
            'sesiones_activas' => Sesion::where('estado', 'activa')->count(),
            'sesiones_hoy' => Sesion::whereDate('fecha', today())->count(),
            'tiempo_total_hoy' => $this->calcularTiempoRealMaquina($sesionesHoyFinalizadas),
        ];

        $sesionesActivas = Sesion::activas()
                                ->with('alumno')
                                ->get()
                                ->map(function($sesion) {
                                    return [
                                        'id' => $sesion->id,
                                        'alumno' => $sesion->alumno->nombre_completo,
                                        'npi' => $sesion->alumno->npi,
                                        'tiempo_transcurrido' => $sesion->tiempo_transcurrido,
                                        'necesita_atencion' => $sesion->necesitaAtencion(),
                                        'hora_inicio' => $sesion->hora_inicio->format('H:i'),
                                    ];
                                });

        return response()->json([
            'estadisticas' => $estadisticas,
            'sesiones_activas' => $sesionesActivas,
            'timestamp' => now()->format('H:i:s')
        ]);
    }

    public function sesionesActivasAjax()
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
     * Algoritmo robusto para calcular tiempo real:
     * 1. Maneja duplicados de IDs.
     * 2. Detecta cruce de medianoche (Fin < Inicio -> +1 día).
     * 3. Calcula solapamientos correctamente.
     */
    private function calcularTiempoRealMaquina($sesiones)
    {
        if ($sesiones->isEmpty()) return 0;

        $sesionesUnicas = $sesiones->unique('id');

        $intervalos = $sesionesUnicas->map(function ($sesion) {
            // Forzamos solo hora para evitar problemas de fechas dobles
            $horaInicioStr = $sesion->hora_inicio instanceof \Carbon\Carbon 
                ? $sesion->hora_inicio->format('H:i:s') 
                : $sesion->hora_inicio;

            $inicio = Carbon::parse($sesion->fecha->format('Y-m-d') . ' ' . $horaInicioStr);

            if ($sesion->hora_fin) {
                $horaFinStr = $sesion->hora_fin instanceof \Carbon\Carbon 
                    ? $sesion->hora_fin->format('H:i:s') 
                    : $sesion->hora_fin;
                
                $fin = Carbon::parse($sesion->fecha->format('Y-m-d') . ' ' . $horaFinStr);

                // CORRECCIÓN MEDIANOCHE: Si termina "antes" de empezar (00:27 < 23:53), es mañana.
                if ($fin->lt($inicio)) {
                    $fin->addDay();
                }
            } else {
                $fin = now();
                // Lo mismo para sesiones activas que cruzan la noche
                if ($fin->lt($inicio)) {
                    $fin->addDay();
                }
            }
            
            return [
                'inicio' => $inicio,
                'fin'    => $fin
            ];
        })->sortBy('inicio')->values();

        if ($intervalos->isEmpty()) return 0;

        $tiempoTotalMinutos = 0;
        
        $inicioActual = $intervalos[0]['inicio'];
        $finActual    = $intervalos[0]['fin'];

        foreach ($intervalos as $intervalo) {
            // Si el nuevo intervalo empieza DESPUÉS de que terminó el actual (hay un hueco)
            if ($intervalo['inicio']->gt($finActual)) {
                // Sumamos el bloque anterior al total
                // CORRECCIÓN NEGATIVO: Usamos abs() para asegurar positivo
                $tiempoTotalMinutos += abs($finActual->diffInMinutes($inicioActual));
                
                // Nuevo bloque
                $inicioActual = $intervalo['inicio'];
                $finActual    = $intervalo['fin'];
            } 
            else {
                // Si se solapan, extendemos el final
                if ($intervalo['fin']->gt($finActual)) {
                    $finActual = $intervalo['fin'];
                }
            }
        }

        // Sumar el último bloque pendiente
        $tiempoTotalMinutos += abs($finActual->diffInMinutes($inicioActual));

        return $tiempoTotalMinutos;
    }

    private function getEstadisticasSemana()
    {
        $estadisticasSemana = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            
            $sesionesDelDia = Sesion::whereDate('fecha', $fecha)
                                    ->get(['id', 'estado', 'fecha', 'hora_inicio', 'hora_fin', 'duracion_minutos']); 
            
            $sesionesFinalizadas = $sesionesDelDia->where('estado', 'finalizada');

            $minutosReales = $this->calcularTiempoRealMaquina($sesionesFinalizadas);

            $estadisticasSemana[] = [
                'dia_nombre' => ucfirst($fecha->locale('es')->isoFormat('ddd')),
                'fecha_corta' => $fecha->format('d/m'),
                'sesiones' => $sesionesDelDia->count(),
                'minutos' => $minutosReales,
            ];
        }

        return $estadisticasSemana;
    }
}