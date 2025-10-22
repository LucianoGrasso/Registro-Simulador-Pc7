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
    /**
     * Dashboard principal - redirige según el rol
     */
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            return $this->dashboardAdmin();
        } else {
            // Operadores tienen su propio dashboard
            return $this->dashboardOperador();
        }
    }

    /**
     * Dashboard para operadores (sin permisos de admin)
     */
    private function dashboardOperador()
    {
        // Estadísticas básicas que pueden ver los operadores
        $estadisticas = [
            'sesiones_hoy' => Sesion::whereDate('fecha', today())->count(),
            'sesiones_activas' => Sesion::where('estado', 'activa')->count(),
            'tiempo_total_hoy' => Sesion::whereDate('fecha', today())
                                      ->where('estado', 'finalizada')
                                      ->sum('duracion_minutos'),
            'alumnos_hoy' => Sesion::whereDate('fecha', today())->distinct('alumno_id')->count(),
        ];

        // Alumnos más activos del mes
        $alumnosActivos = Alumno::withCount(['sesiones' => function($query) {
                                    $query->whereMonth('fecha', date('m'))
                                          ->whereYear('fecha', date('Y'));
                                }])
                                ->having('sesiones_count', '>', 0)
                                ->orderBy('sesiones_count', 'desc')
                                ->limit(5)
                                ->get();
                            

        // Sesiones activas actuales
        $sesionesActivas = Sesion::activas()
                                ->with(['alumno', 'usuarioInicio'])
                                ->orderBy('hora_inicio', 'asc')
                                ->get();

        // Sesiones recientes del día (últimas 15)
        $sesionesRecientes = Sesion::whereDate('fecha', today())
                                  ->with(['alumno', 'usuarioInicio', 'usuarioFin'])
                                  ->orderBy('hora_inicio', 'desc')
                                  ->limit(15)
                                  ->get();

        // Estadísticas de los últimos 7 días (solo para mostrar tendencia)
        $estadisticasSemana = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            $sesionesDelDia = Sesion::whereDate('fecha', $fecha)->get();
            
            $estadisticasSemana[] = [
                'fecha_completa' => $fecha,
                'fecha' => $fecha->format('d/m'),
                'dia' => ucfirst($fecha->locale('es')->isoFormat('dddd')), // Capitalizar aquí
                'sesiones' => $sesionesDelDia->count(),
                'minutos' => $sesionesDelDia->where('estado', 'finalizada')->sum('duracion_minutos'),
            ];
        }

        // Sesiones que necesitan atención
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

    /**
     * Dashboard para administradores
     */
    private function dashboardAdmin()
    {
        // Estadísticas generales
        $estadisticas = [
            'total_alumnos' => Alumno::where('is_active', true)->count(),
            'total_sesiones_hoy' => Sesion::whereDate('fecha', today())->count(),
            'sesiones_activas' => Sesion::where('estado', 'activa')->count(),
            'tiempo_total_hoy' => Sesion::whereDate('fecha', today())
                                      ->where('estado', 'finalizada')
                                      ->sum('duracion_minutos'),
        ];

        // Sesiones activas actuales
        $sesionesActivas = Sesion::activas()
                                ->with(['alumno', 'usuarioInicio'])
                                ->orderBy('hora_inicio', 'asc')
                                ->get();

        // Sesiones recientes (últimas 10)
        $sesionesRecientes = Sesion::with(['alumno', 'usuarioInicio', 'usuarioFin'])
                                  ->orderBy('created_at', 'desc')
                                  ->limit(10)
                                  ->get();

        // Estadísticas de los últimos 7 días
        $estadisticasSemana = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            $sesionesDelDia = Sesion::whereDate('fecha', $fecha)->get();
            
            $estadisticasSemana[] = [
                'fecha' => $fecha->format('d/m'),
                'dia' => $fecha->format('D'),
                'sesiones' => $sesionesDelDia->count(),
                'minutos' => $sesionesDelDia->where('estado', 'finalizada')->sum('duracion_minutos'),
                'alumnos_unicos' => $sesionesDelDia->unique('alumno_id')->count(),
            ];
        }

        // Alumnos más activos del mes
        $alumnosActivos = Alumno::withCount(['sesiones' => function($query) {
                                    $query->whereMonth('fecha', date('m'))
                                          ->whereYear('fecha', date('Y'));
                                }])
                                ->having('sesiones_count', '>', 0)
                                ->orderBy('sesiones_count', 'desc')
                                ->limit(5)
                                ->get();

        // Sesiones que necesitan atención (muy largas)
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

    /**
     * API para actualizar dashboard en tiempo real
     */
    public function datosActualizados()
    {
        $estadisticas = [
            'sesiones_activas' => Sesion::where('estado', 'activa')->count(),
            'sesiones_hoy' => Sesion::whereDate('fecha', today())->count(),
            'tiempo_total_hoy' => Sesion::whereDate('fecha', today())
                                      ->where('estado', 'finalizada')
                                      ->sum('duracion_minutos'),
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
    /**
     * AJAX: Obtener sesiones activas para el dashboard
     */
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
}