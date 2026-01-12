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
        // Obtenemos las sesiones de hoy finalizadas para calcular el solapamiento
        $sesionesHoyFinalizadas = Sesion::whereDate('fecha', today())
                                    ->where('estado', 'finalizada')
                                    ->get();

        $estadisticas = [
            'sesiones_hoy' => Sesion::whereDate('fecha', today())->count(),
            'sesiones_activas' => Sesion::where('estado', 'activa')->count(),
            // Usamos la función corregida
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
            // Usamos la función corregida
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
                                ->limit(5)
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
            // Usamos la función corregida
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
     * CORRECCIÓN PRINCIPAL:
     * 1. Maneja fechas duplicadas forzando formato H:i:s
     * 2. Calcula la diferencia SIEMPRE positiva (Inicio -> Fin)
     */
    private function calcularTiempoRealMaquina($sesiones)
    {
        if ($sesiones->isEmpty()) return 0;

        $intervalos = $sesiones->map(function ($sesion) {
            // Forzamos formato solo hora para evitar "Double date specification"
            $horaInicioStr = $sesion->hora_inicio instanceof \Carbon\Carbon 
                ? $sesion->hora_inicio->format('H:i:s') 
                : $sesion->hora_inicio;

            $inicio = Carbon::parse($sesion->fecha->format('Y-m-d') . ' ' . $horaInicioStr);

            if ($sesion->hora_fin) {
                $horaFinStr = $sesion->hora_fin instanceof \Carbon\Carbon 
                    ? $sesion->hora_fin->format('H:i:s') 
                    : $sesion->hora_fin;
                
                $fin = Carbon::parse($sesion->fecha->format('Y-m-d') . ' ' . $horaFinStr);
            } else {
                $fin = now();
            }
            
            return [
                'inicio' => $inicio,
                'fin'    => $fin
            ];
        })->sortBy('inicio')->values();

        $tiempoTotalMinutos = 0;
        
        if ($intervalos->isEmpty()) return 0;

        $inicioActual = $intervalos[0]['inicio'];
        $finActual    = $intervalos[0]['fin'];

        foreach ($intervalos as $intervalo) {
            if ($intervalo['inicio']->gt($finActual)) {
                // CORRECCION: Inicio -> Fin (diffInMinutes)
                // Usamos abs() por seguridad, aunque el orden lógico ya es correcto
                $tiempoTotalMinutos += abs($inicioActual->diffInMinutes($finActual));
                
                $inicioActual = $intervalo['inicio'];
                $finActual    = $intervalo['fin'];
            } 
            else {
                if ($intervalo['fin']->gt($finActual)) {
                    $finActual = $intervalo['fin'];
                }
            }
        }

        // Sumar último bloque (CORREGIDO: Inicio -> Fin)
        $tiempoTotalMinutos += abs($inicioActual->diffInMinutes($finActual));

        return $tiempoTotalMinutos;
    }

    private function getEstadisticasSemana()
    {
        $estadisticasSemana = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            
            // Necesitamos hora_inicio y hora_fin para el cálculo preciso
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