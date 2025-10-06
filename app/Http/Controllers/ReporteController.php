<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ReporteController extends Controller
{
    /**
     * Vista principal de reportes (solo admin)
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('sesiones.scanner')
                           ->with('error', 'No tienes permisos para ver reportes');
        }

        return view('reportes.index');
    }

    /**
     * Reporte mensual
     */
    public function mensual(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $año = $request->get('año', now()->year);
        
        $fechaInicio = Carbon::create($año, $mes, 1);
        $fechaFin = $fechaInicio->copy()->endOfMonth();

        // Sesiones del mes
        $sesiones = Sesion::whereBetween('fecha', [$fechaInicio, $fechaFin])
                         ->with(['alumno', 'usuarioInicio', 'usuarioFin'])
                         ->get();

        // Estadísticas mensuales
        $stats = [
            'periodo' => $fechaInicio->locale('es')->isoFormat('MMMM [de] YYYY'),
            'mes' => $mes,
            'año' => $año,
            'total_sesiones' => $sesiones->count(),
            'sesiones_finalizadas' => $sesiones->where('estado', 'finalizada')->count(),
            'alumnos_activos' => $sesiones->unique('alumno_id')->count(),
            'tiempo_total_horas' => round($sesiones->where('estado', 'finalizada')->sum('duracion_minutos') / 60, 2),
            'promedio_diario' => round($sesiones->count() / $fechaInicio->daysInMonth, 1),
            'tiempo_promedio' => round($sesiones->where('estado', 'finalizada')->avg('duracion_minutos'), 1),
        ];

        // Sesiones por día del mes
        $sesionesPorDia = [];
        for ($dia = 1; $dia <= $fechaInicio->daysInMonth; $dia++) {
            $fecha = Carbon::create($año, $mes, $dia);
            $sesionesPorDia[$dia] = $sesiones->filter(function($sesion) use ($fecha) {
                return $sesion->fecha->isSameDay($fecha);
            })->count();
        }

        // Top alumnos del mes
        $topAlumnosMes = $sesiones->groupBy('alumno_id')
                                ->map(function($sesionesAlumno) {
                                    return [
                                        'alumno' => $sesionesAlumno->first()->alumno,
                                        'total_sesiones' => $sesionesAlumno->count(),
                                        'tiempo_total' => $sesionesAlumno->where('estado', 'finalizada')->sum('duracion_minutos'),
                                        'promedio_duracion' => round($sesionesAlumno->where('estado', 'finalizada')->avg('duracion_minutos'), 1)
                                    ];
                                })
                                ->sortByDesc('total_sesiones')
                                ->take(10);

        // Comparación con mes anterior
        $mesAnterior = $fechaInicio->copy()->subMonth();
        $sesionesAnterior = Sesion::whereBetween('fecha', [$mesAnterior, $mesAnterior->copy()->endOfMonth()])->count();
        $crecimiento = $sesionesAnterior > 0 ? round((($sesiones->count() - $sesionesAnterior) / $sesionesAnterior) * 100, 1) : 0;

        return view('reportes.mensual', compact('stats', 'sesionesPorDia', 'topAlumnosMes', 'crecimiento'));
    }

    /**
     * Reporte anual
     */
    public function anual(Request $request)
    {
        $año = $request->get('año', now()->year);
        
        $fechaInicio = Carbon::create($año, 1, 1);
        $fechaFin = $fechaInicio->copy()->endOfYear();

        // Sesiones del año
        $sesiones = Sesion::whereBetween('fecha', [$fechaInicio, $fechaFin])
                        ->with(['alumno', 'usuarioInicio', 'usuarioFin'])
                        ->get();

        // Estadísticas anuales
        $stats = [
            'año' => $año,
            'total_sesiones' => $sesiones->count(),
            'sesiones_finalizadas' => $sesiones->where('estado', 'finalizada')->count(),
            'alumnos_activos' => $sesiones->unique('alumno_id')->count(),
            'tiempo_total_horas' => round($sesiones->where('estado', 'finalizada')->sum('duracion_minutos') / 60, 2),
            'promedio_mensual' => round($sesiones->count() / 12, 1),
            'tiempo_promedio' => round($sesiones->where('estado', 'finalizada')->avg('duracion_minutos'), 1),
        ];

        // Sesiones por mes
        $sesionesPorMes = [];
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        foreach ($meses as $numeroMes => $nombreMes) {
            $inicioMes = Carbon::create($año, $numeroMes, 1);
            $finMes = $inicioMes->copy()->endOfMonth();
            $sesionesPorMes[$nombreMes] = $sesiones->filter(function($sesion) use ($inicioMes, $finMes) {
                return $sesion->fecha->between($inicioMes, $finMes);
            })->count();
        }

        // Top alumnos del año
        $topAlumnosAño = $sesiones->groupBy('alumno_id')
                                ->map(function($sesionesAlumno) {
                                    return [
                                        'alumno' => $sesionesAlumno->first()->alumno,
                                        'total_sesiones' => $sesionesAlumno->count(),
                                        'tiempo_total' => $sesionesAlumno->where('estado', 'finalizada')->sum('duracion_minutos'),
                                        'promedio_duracion' => round($sesionesAlumno->where('estado', 'finalizada')->avg('duracion_minutos'), 1)
                                    ];
                                })
                                ->sortByDesc('total_sesiones')
                                ->take(10);

        return view('reportes.anual', compact('stats', 'sesionesPorMes', 'topAlumnosAño'));
    }

    /**
     * Datos para resumen rápido (AJAX)
     */
    public function resumenRapido()
    {
        $hoy = today();
        $inicioMes = now()->startOfMonth();
        $finMes = now()->endOfMonth();
        $inicioAño = now()->startOfYear();
        $finAño = now()->endOfYear();

        $data = [
            'sesiones_hoy' => Sesion::whereDate('fecha', $hoy)->count(),
            'tiempo_promedio' => round(Sesion::whereDate('fecha', $hoy)
                                            ->where('estado', 'finalizada')
                                            ->avg('duracion_minutos') ?? 0),
            'sesiones_mes' => Sesion::whereBetween('fecha', [$inicioMes, $finMes])->count(),
            'sesiones_año' => Sesion::whereBetween('fecha', [$inicioAño, $finAño])->count(),
        ];

        return response()->json($data);
    }

    /**
     * Exportar reportes en diferentes formatos
     */
    public function exportar(Request $request)
    {
        $tipo = $request->get('tipo', 'mensual');
        $formato = $request->get('formato', 'pdf');
        $periodo = $request->get('periodo');

        try {
            switch ($tipo) {
                case 'mensual':
                    return $this->exportarMensual($formato, $periodo);
                case 'anual':
                    return $this->exportarAnual($formato, $periodo);
                default:
                    return response()->json(['error' => 'Tipo de reporte no válido'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al generar el reporte: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Exportar reporte mensual
     */
    private function exportarMensual($formato, $periodo)
    {
        if (!$periodo) {
            return response()->json(['error' => 'Periodo requerido para reporte mensual'], 400);
        }

        [$año, $mes] = explode('-', $periodo);
        
        $fechaInicio = Carbon::create($año, $mes, 1);
        $fechaFin = $fechaInicio->copy()->endOfMonth();

        // Obtener datos del reporte mensual
        $sesiones = Sesion::whereBetween('fecha', [$fechaInicio, $fechaFin])
                         ->with(['alumno', 'usuarioInicio', 'usuarioFin'])
                         ->get();

        $stats = [
            'periodo' => $fechaInicio->locale('es')->isoFormat('MMMM [de] YYYY'),
            'mes' => $mes,
            'año' => $año,
            'total_sesiones' => $sesiones->count(),
            'sesiones_finalizadas' => $sesiones->where('estado', 'finalizada')->count(),
            'alumnos_activos' => $sesiones->unique('alumno_id')->count(),
            'tiempo_total_horas' => round($sesiones->where('estado', 'finalizada')->sum('duracion_minutos') / 60, 2),
            'tiempo_promedio' => round($sesiones->where('estado', 'finalizada')->avg('duracion_minutos'), 1),
        ];

        $nombreArchivo = "reporte_mensual_{$año}_{$mes}";

        if ($formato === 'pdf') {
            return $this->generarPDFMensual($stats, $sesiones, $nombreArchivo);
        } else {
            return $this->generarExcelMensual($stats, $sesiones, $nombreArchivo);
        }
    }

    /**
     * Exportar reporte anual
     */
    private function exportarAnual($formato, $periodo)
    {
        if (!$periodo) {
            return response()->json(['error' => 'Año requerido para reporte anual'], 400);
        }

        $año = $periodo;
        $fechaInicio = Carbon::create($año, 1, 1);
        $fechaFin = $fechaInicio->copy()->endOfYear();

        // Obtener datos del reporte anual
        $sesiones = Sesion::whereBetween('fecha', [$fechaInicio, $fechaFin])
                         ->with(['alumno', 'usuarioInicio', 'usuarioFin'])
                         ->get();

        $stats = [
            'año' => $año,
            'total_sesiones' => $sesiones->count(),
            'sesiones_finalizadas' => $sesiones->where('estado', 'finalizada')->count(),
            'alumnos_activos' => $sesiones->unique('alumno_id')->count(),
            'tiempo_total_horas' => round($sesiones->where('estado', 'finalizada')->sum('duracion_minutos') / 60, 2),
            'tiempo_promedio' => round($sesiones->where('estado', 'finalizada')->avg('duracion_minutos'), 1),
        ];

        $nombreArchivo = "reporte_anual_{$año}";

        if ($formato === 'pdf') {
            return $this->generarPDFAnual($stats, $sesiones, $nombreArchivo);
        } else {
            return $this->generarExcelAnual($stats, $sesiones, $nombreArchivo);
        }
    }

    /**
     * Generar PDF para reporte mensual
     */
    private function generarPDFMensual($stats, $sesiones, $nombreArchivo)
    {
        // Crear HTML para el PDF
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title> Reporte Mensual - {$stats['periodo']}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
                .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
                .stats { display: flex; justify-content: space-around; margin: 20px 0; }
                .stat-box { text-align: center; border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
                .stat-number { font-size: 24px; font-weight: bold; color: #007bff; }
                .stat-label { font-size: 12px; color: #666; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f8f9fa; font-weight: bold; }
                .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Reporte Mensual del Simulador</h1>
                <h2>{$stats['periodo']}</h2>
                <p>Generado el " . now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY [a las] HH:mm') . "</p>
            </div>

            <div class='stats'>
                <div class='stat-box'>
                    <div class='stat-number'>{$stats['total_sesiones']}</div>
                    <div class='stat-label'>Total Sesiones</div>
                </div>
                <div class='stat-box'>
                    <div class='stat-number'>{$stats['sesiones_finalizadas']}</div>
                    <div class='stat-label'>Sesiones Finalizadas</div>
                </div>
                <div class='stat-box'>
                    <div class='stat-number'>{$stats['alumnos_activos']}</div>
                    <div class='stat-label'>Alumnos Activos</div>
                </div>
                <div class='stat-box'>
                    <div class='stat-number'>{$stats['tiempo_total_horas']}h</div>
                    <div class='stat-label'>Tiempo Total</div>
                </div>
            </div>

            <h3>Detalle de Sesiones</h3>
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno</th>
                        <th>NPI</th>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Duración</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($sesiones->sortBy('fecha') as $sesion) {
            $duracion = $sesion->duracion_minutos ? $sesion->duracion_minutos . ' min' : '-';
            $horaFin = $sesion->hora_fin ? $sesion->hora_fin->format('H:i') : '-';
            
            $html .= "
                    <tr>
                        <td>" . $sesion->fecha->format('d/m/Y') . "</td>
                        <td>" . $sesion->alumno->nombre_completo . "</td>
                        <td>" . $sesion->alumno->npi . "</td>
                        <td>" . $sesion->hora_inicio->format('H:i') . "</td>
                        <td>{$horaFin}</td>
                        <td>{$duracion}</td>
                        <td>" . ucfirst($sesion->estado) . "</td>
                    </tr>";
        }

        $html .= "
                </tbody>
            </table>

            <div class='footer'>
                <p>Sistema de Registro de Simulador - Reporte generado automáticamente</p>
            </div>
        </body>
        </html>";
        
        $pdf = PDF::loadHTML($html);
        return $pdf->download($nombreArchivo . '.pdf');
    }

    /**
     * Generar Excel para reporte mensual
     */
    private function generarExcelMensual($stats, $sesiones, $nombreArchivo)
    {
        // Crear CSV como alternativa simple a Excel
        $csv = "Reporte Mensual - {$stats['periodo']}\n";
        $csv .= "Generado el: " . now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY [a las] HH:mm') . "\n\n";
        
        $csv .= "RESUMEN EJECUTIVO\n";
        $csv .= "Total Sesiones,{$stats['total_sesiones']}\n";
        $csv .= "Sesiones Finalizadas,{$stats['sesiones_finalizadas']}\n";
        $csv .= "Alumnos Activos,{$stats['alumnos_activos']}\n";
        $csv .= "Tiempo Total (horas),{$stats['tiempo_total_horas']}\n";
        $csv .= "Tiempo Promedio (minutos),{$stats['tiempo_promedio']}\n\n";
        
        $csv .= "DETALLE DE SESIONES\n";
        $csv .= "Fecha,Alumno,NPI,Hora Inicio,Hora Fin,Duración (min),Estado,Actividad\n";
        
        foreach ($sesiones->sortBy('fecha') as $sesion) {
            $duracion = $sesion->duracion_minutos ?: '-';
            $horaFin = $sesion->hora_fin ? $sesion->hora_fin->format('H:i') : '-';
            $actividad = str_replace(['"', ',', "\n", "\r"], [' ', ' ', ' ', ' '], $sesion->actividad);
            
            $csv .= '"' . $sesion->fecha->format('d/m/Y') . '",';
            $csv .= '"' . $sesion->alumno->nombre_completo . '",';
            $csv .= '"' . $sesion->alumno->npi . '",';
            $csv .= '"' . $sesion->hora_inicio->format('H:i') . '",';
            $csv .= '"' . $horaFin . '",';
            $csv .= '"' . $duracion . '",';
            $csv .= '"' . ucfirst($sesion->estado) . '",';
            $csv .= '"' . $actividad . '"' . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$nombreArchivo}.csv\"");
    }

    /**
     * Generar PDF para reporte anual
     */
    private function generarPDFAnual($stats, $sesiones, $nombreArchivo)
    {
        // Calcular sesiones por mes
        $sesionesPorMes = [];
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        foreach ($meses as $numeroMes => $nombreMes) {
            $sesionesMes = $sesiones->filter(function($sesion) use ($numeroMes) {
                return $sesion->fecha->month == $numeroMes;
            });
            
            $sesionesPorMes[] = [
                'mes' => $nombreMes,
                'sesiones' => $sesionesMes->count(),
                'finalizadas' => $sesionesMes->where('estado', 'finalizada')->count(),
                'tiempo_total' => round($sesionesMes->where('estado', 'finalizada')->sum('duracion_minutos') / 60, 1),
                'alumnos' => $sesionesMes->unique('alumno_id')->count()
            ];
        }

        // Top 5 alumnos del año (ordenado de mayor a menor)
        $topAlumnos = $sesiones->groupBy('alumno_id')
                            ->map(function($sesionesAlumno) {
                                return [
                                    'nombre' => $sesionesAlumno->first()->alumno->nombre_completo,
                                    'npi' => $sesionesAlumno->first()->alumno->npi,
                                    'sesiones' => $sesionesAlumno->count(),
                                    'tiempo' => round($sesionesAlumno->where('estado', 'finalizada')->sum('duracion_minutos') / 60, 1)
                                ];
                            })
                            ->sortByDesc('sesiones')
                            ->take(5)
                            ->values(); // Reindexar para tener orden correcto

        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Reporte Anual - {$stats['año']}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; color: #333; font-size: 12px; }
                .header { text-align: center; border-bottom: 3px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
                .stats { display: flex; justify-content: space-around; margin: 20px 0; flex-wrap: wrap; }
                .stat-box { text-align: center; border: 1px solid #ddd; padding: 15px; border-radius: 5px; margin: 5px; min-width: 120px; }
                .stat-number { font-size: 24px; font-weight: bold; color: #007bff; }
                .stat-label { font-size: 11px; color: #666; margin-top: 5px; }
                .section { margin: 25px 0; }
                .section-title { font-size: 16px; font-weight: bold; color: #333; border-bottom: 2px solid #007bff; padding-bottom: 8px; margin-bottom: 15px; }
                .monthly-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin: 15px 0; }
                .month-card { border: 1px solid #ddd; padding: 12px; border-radius: 5px; background: #f9f9f9; }
                .month-name { font-weight: bold; color: #007bff; margin-bottom: 8px; }
                .month-stat { margin: 3px 0; font-size: 11px; }
                .month-stat strong { color: #333; }
                table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
                th { background-color: #f8f9f9; font-weight: bold; }
                .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 15px; }
                .summary-box { background: #f0f8ff; border: 1px solid #007bff; padding: 15px; border-radius: 5px; margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Reporte Anual del Simulador</h1>
                <h2>Año {$stats['año']}</h2>
                <p>Generado el " . now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY [a las] HH:mm') . "</p>
            </div>

            <div class='stats'>
                <div class='stat-box'>
                    <div class='stat-number'>{$stats['total_sesiones']}</div>
                    <div class='stat-label'>Total Sesiones</div>
                </div>
                <div class='stat-box'>
                    <div class='stat-number'>{$stats['sesiones_finalizadas']}</div>
                    <div class='stat-label'>Sesiones Finalizadas</div>
                </div>
                <div class='stat-box'>
                    <div class='stat-number'>{$stats['alumnos_activos']}</div>
                    <div class='stat-label'>Alumnos Únicos</div>
                </div>
                <div class='stat-box'>
                    <div class='stat-number'>{$stats['tiempo_total_horas']}h</div>
                    <div class='stat-label'>Tiempo Total</div>
                </div>
            </div>

            <div class='section'>
                <div class='section-title'>Resumen Mensual</div>
                <div class='monthly-grid'>";

        foreach ($sesionesPorMes as $mes) {
            $html .= "
                    <div class='month-card'>
                        <div class='month-name'>{$mes['mes']}</div>
                        <div class='month-stat'><strong>Sesiones:</strong> {$mes['sesiones']}</div>
                        <div class='month-stat'><strong>Finalizadas:</strong> {$mes['finalizadas']}</div>
                        <div class='month-stat'><strong>Tiempo:</strong> {$mes['tiempo_total']}h</div>
                        <div class='month-stat'><strong>Alumnos:</strong> {$mes['alumnos']}</div>
                    </div>";
        }

        $html .= "
                </div>
            </div>

            <div class='section'>
                <div class='section-title'>Top Alumnos del Año</div>
                <table>
                    <thead>
                        <tr>
                            <th>Posición</th>
                            <th>Alumno</th>
                            <th>NPI</th>
                            <th>Sesiones</th>
                            <th>Tiempo Total</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($topAlumnos as $index => $alumno) {
            $posicion = $index + 1;
            
            $html .= "
                        <tr>
                            <td style='text-align: center; font-size: 14px; font-weight: bold;'>{$posicion}</td>
                            <td>{$alumno['nombre']}</td>
                            <td>{$alumno['npi']}</td>
                            <td style='text-align: center; font-weight: bold; color: #007bff;'>{$alumno['sesiones']}</td>
                            <td style='text-align: center;'>{$alumno['tiempo']}h</td>
                        </tr>";
        }

        $html .= "
                    </tbody>
                </table>
            </div>

            <div class='section'>
                <div class='section-title'>Análisis por Trimestres</div>
                <div style='display: flex; justify-content: space-between; flex-wrap: wrap;'>";

        $trimestres = [
            'T1' => ['meses' => [1,2,3], 'nombre' => 'Primer Trimestre (Ene-Mar)'],
            'T2' => ['meses' => [4,5,6], 'nombre' => 'Segundo Trimestre (Abr-Jun)'],
            'T3' => ['meses' => [7,8,9], 'nombre' => 'Tercer Trimestre (Jul-Sep)'],
            'T4' => ['meses' => [10,11,12], 'nombre' => 'Cuarto Trimestre (Oct-Dic)']
        ];

        foreach ($trimestres as $t => $trimestre) {
            $sesionesTrimestre = $sesiones->filter(function($sesion) use ($trimestre) {
                return in_array($sesion->fecha->month, $trimestre['meses']);
            });
            
            $totalTrimestre = $sesionesTrimestre->count();
            $promedioMensual = round($totalTrimestre / 3, 1);
            
            $html .= "
                    <div style='border: 1px solid #007bff; padding: 15px; border-radius: 5px; margin: 5px; min-width: 200px; text-align: center;'>
                        <div style='font-weight: bold; color: #007bff; margin-bottom: 8px;'>{$t}</div>
                        <div style='font-size: 20px; font-weight: bold; color: #333;'>{$totalTrimestre}</div>
                        <div style='font-size: 10px; color: #666; margin-top: 5px;'>{$trimestre['nombre']}</div>
                        <div style='font-size: 10px; color: #666;'>Promedio: {$promedioMensual} ses/mes</div>
                    </div>";
        }

        $html .= "
                </div>
            </div>

            <div class='footer'>
                <p><strong>Sistema de Registro de Simulador</strong></p>
                <p>Reporte generado automáticamente • Año {$stats['año']}</p>
            </div>
        </body>
        </html>";

        $pdf = PDF::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'sans-serif']);
        
        return $pdf->download($nombreArchivo . '.pdf');
    }

    /**
     * Generar Excel para reporte anual
     */
    private function generarExcelAnual($stats, $sesiones, $nombreArchivo)
    {
        $csv = "Reporte Anual - {$stats['año']}\n";
        $csv .= "Generado el: " . now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY [a las] HH:mm') . "\n\n";
        
        $csv .= "RESUMEN EJECUTIVO\n";
        $csv .= "Total Sesiones,{$stats['total_sesiones']}\n";
        $csv .= "Sesiones Finalizadas,{$stats['sesiones_finalizadas']}\n";
        $csv .= "Alumnos Únicos,{$stats['alumnos_activos']}\n";
        $csv .= "Tiempo Total (horas),{$stats['tiempo_total_horas']}\n";
        $csv .= "Tiempo Promedio (minutos),{$stats['tiempo_promedio']}\n\n";
        
        // Agregar resumen mensual
        $csv .= "RESUMEN POR MESES\n";
        $csv .= "Mes,Sesiones\n";
        
        $sesionesPorMes = $sesiones->groupBy(function($sesion) {
            return $sesion->fecha->locale('es')->isoFormat('MMMM');
        });
        
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                  
        foreach ($meses as $mes) {
            $cantidad = $sesionesPorMes[$mes]->count() ?? 0;
            $csv .= "{$mes},{$cantidad}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$nombreArchivo}.csv\"");
    }
    
}