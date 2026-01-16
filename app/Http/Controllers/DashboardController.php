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
     * Algoritmo corregido:
     * 1. Detecta cruce de medianoche (ej: 23:50 a 00:20).
     * 2. Evita duplicados.
     */
    private function calcularTiempoRealMaquina($sesiones)
    {
        if ($sesiones->isEmpty()) return 0;

        // CORRECCIÓN 1: Asegurar unicidad por ID para evitar duplicados si hay joins raros
        $sesionesUnicas = $sesiones->unique('id');

        $intervalos = $sesionesUnicas->map(function ($sesion) {
            
            $horaInicioStr = $sesion->hora_inicio instanceof \Carbon\Carbon 
                ? $sesion->hora_inicio->format('H:i:s') 
                : $sesion->hora_inicio;

            $inicio = Carbon::parse($sesion->fecha->format('Y-m-d') . ' ' . $horaInicioStr);

            if ($sesion->hora_fin) {
                $horaFinStr = $sesion->hora_fin instanceof \Carbon\Carbon 
                    ? $sesion->hora_fin->format('H:i:s') 
                    : $sesion->hora_fin;
                
                $fin = Carbon::parse($sesion->fecha->format('Y-m-d') . ' ' . $horaFinStr);

                // CORRECCIÓN 2: Si la hora fin es MENOR que la inicio (ej: 00:27 < 23:53),
                // significa que es el día siguiente. Sumamos un día.
                if ($fin->lt($inicio)) {
                    $fin->addDay();
                }

            } else {
                $fin = now();
                // Si la sesión activa empezó ayer (cruce de medianoche en vivo)
                if ($fin->lt($inicio)) {
                    $fin->addDay();
                }
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
            // Si hay hueco entre el fin actual y el nuevo inicio
            if ($intervalo['inicio']->gt($finActual)) {
                $tiempoTotalMinutos += $finActual->diffInMinutes($inicioActual);
                $inicioActual = $intervalo['inicio'];
                $finActual    = $intervalo['fin'];
            } 
            else {
                // Si se solapan, extendemos el final si es necesario
                if ($intervalo['fin']->gt($finActual)) {
                    $finActual = $intervalo['fin'];
                }
            }
        }

        $tiempoTotalMinutos += $finActual->diffInMinutes($inicioActual);

        return $tiempoTotalMinutos;
    }

    private function getEstadisticasSemana()
    {
        $estadisticasSemana = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            
            // Obtenemos todos los campos necesarios
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