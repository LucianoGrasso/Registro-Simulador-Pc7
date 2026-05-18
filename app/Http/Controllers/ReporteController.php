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
     * Datos para resumen rápido GLOBAL e HISTÓRICO (AJAX)
     * Este método alimenta los cuadros de la página principal de reportes
     */
    public function resumenRapido()
    {
        // 1. Total Histórico de Sesiones (Todas)
        $totalSesiones = Sesion::count();

        // 2. Horas Totales Históricas (Práctica + Instrucción)
        $totalMinutos = Sesion::where('estado', 'finalizada')->sum('duracion_minutos');
        $horasTotales = $totalMinutos > 0 ? round($totalMinutos / 60, 1) : 0;

        // 3. Tiempo Promedio Global
        $tiempoPromedio = Sesion::where('estado', 'finalizada')->avg('duracion_minutos');

        // 4. Ahorro Total Histórico (Horas * 650 USD)
        $ahorroTotal = $horasTotales * 650;

        // --- NUEVO: ESTADÍSTICAS OFICIALES DE INSTRUCCIÓN ---
        $totalInstruccion = Sesion::where('es_instruccion', true)->count();
        
        $minutosInstruccion = Sesion::where('estado', 'finalizada')
                                    ->where('es_instruccion', true)
                                    ->sum('duracion_minutos');
                                    
        $horasInstruccion = $minutosInstruccion > 0 ? round($minutosInstruccion / 60, 1) : 0;
        // ----------------------------------------------------

        $data = [
            'total_historico_sesiones'   => number_format($totalSesiones, 0, ',', '.'),
            'tiempo_promedio_global'     => round($tiempoPromedio ?? 0, 0),
            'horas_totales_global'       => number_format($horasTotales, 1, ',', '.'),
            'ahorro_total_global'        => number_format($ahorroTotal, 0, ',', '.'),
            
            // Nuevos datos que enviaremos a la vista
            'total_sesiones_instruccion' => number_format($totalInstruccion, 0, ',', '.'),
            'horas_instruccion_global'   => number_format($horasInstruccion, 1, ',', '.'),
        ];

        return response()->json($data);
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

        // Estadísticas mensuales (AGREGAMOS LAS DE INSTRUCCIÓN)
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
            
            // --- NUEVOS DATOS OFICIALES ---
            'sesiones_instruccion' => $sesiones->where('es_instruccion', true)->count(),
            'horas_instruccion' => round($sesiones->where('estado', 'finalizada')->where('es_instruccion', true)->sum('duracion_minutos') / 60, 2),
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
                                ->take(5); // Lo bajé a 5 para que cuadre mejor con el nuevo diseño

        // --- NUEVO: TOP PRUEBAS SYLLABUS DEL MES ---
        $topPruebas = $sesiones->where('es_instruccion', true)
                               ->whereNotNull('codigo_prueba')
                               ->groupBy('codigo_prueba')
                               ->map(function($grupo) {
                                   return [
                                       'codigo' => $grupo->first()->codigo_prueba,
                                       'cantidad' => $grupo->count()
                                   ];
                               })
                               ->sortByDesc('cantidad')
                               ->take(5);

        // Comparación con mes anterior
        $mesAnterior = $fechaInicio->copy()->subMonth();
        $sesionesAnterior = Sesion::whereBetween('fecha', [$mesAnterior, $mesAnterior->copy()->endOfMonth()])->count();
        $crecimiento = $sesionesAnterior > 0 ? round((($sesiones->count() - $sesionesAnterior) / $sesionesAnterior) * 100, 1) : 0;

        return view('reportes.mensual', compact('stats', 'sesionesPorDia', 'topAlumnosMes', 'crecimiento', 'topPruebas'));
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
            
            // --- NUEVOS DATOS OFICIALES ---
            'sesiones_instruccion' => $sesiones->where('es_instruccion', true)->count(),
            'horas_instruccion' => round($sesiones->where('estado', 'finalizada')->where('es_instruccion', true)->sum('duracion_minutos') / 60, 2),
        ];

        // Sesiones por mes (Usamos abreviaciones para que se vea mejor el gráfico)
        $sesionesPorMes = [];
        $meses = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
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
                                ->take(5);

        // --- NUEVO: TOP PRUEBAS SYLLABUS DEL AÑO ---
        $topPruebas = $sesiones->where('es_instruccion', true)
                               ->whereNotNull('codigo_prueba')
                               ->groupBy('codigo_prueba')
                               ->map(function($grupo) {
                                   return [
                                       'codigo' => $grupo->first()->codigo_prueba,
                                       'cantidad' => $grupo->count()
                                   ];
                               })
                               ->sortByDesc('cantidad')
                               ->take(5);

        return view('reportes.anual', compact('stats', 'sesionesPorMes', 'topAlumnosAño', 'topPruebas'));
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
        // Validación extra para evitar errores
        if (empty($periodo) || !str_contains($periodo, '-')) {
            return response()->json(['error' => 'Formato de periodo inválido o faltante'], 400);
        }

        [$año, $mes] = explode('-', $periodo);
        
        $fechaInicio = Carbon::create($año, $mes, 1);
        $fechaFin = $fechaInicio->copy()->endOfMonth();

        // Obtener datos del reporte mensual
        $sesiones = Sesion::whereBetween('fecha', [$fechaInicio, $fechaFin])
                          ->with(['alumno', 'usuarioInicio', 'usuarioFin'])
                          ->get();

        // AQUÍ ESTÁ LA CORRECCIÓN: Agregamos todas las variables que el PDF necesita
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
            
            // Datos de vuelos oficiales
            'sesiones_instruccion' => $sesiones->where('es_instruccion', true)->count(),
            'horas_instruccion' => round($sesiones->where('estado', 'finalizada')->where('es_instruccion', true)->sum('duracion_minutos') / 60, 2),
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
            // Nuevos datos
            'sesiones_instruccion' => $sesiones->where('es_instruccion', true)->count(),
            'horas_instruccion' => round($sesiones->where('estado', 'finalizada')->where('es_instruccion', true)->sum('duracion_minutos') / 60, 2),
        ];

        $nombreArchivo = "reporte_anual_{$año}";

        if ($formato === 'pdf') {
            return $this->generarPDFAnual($stats, $sesiones, $nombreArchivo);
        } else {
            return $this->generarExcelAnual($stats, $sesiones, $nombreArchivo);
        }
    }

    /**
     * Genera un gráfico de barras en formato SVG puro (Offline)
     * * @param array $datos Array asociativo ['Etiqueta' => Valor]
     * @return string Código HTML/SVG
     */
    private function generarGraficoSVG($datos)
    {
        $ancho = 700;
        $alto = 300;
        $margen = 40;
        $anchoBarra = ($ancho - (2 * $margen)) / count($datos);
        $espacioBarra = $anchoBarra * 0.2;
        $anchoRealBarra = $anchoBarra - $espacioBarra;
        
        $maxValor = max($datos);
        $maxValor = $maxValor == 0 ? 1 : $maxValor;
        $escalaY = ($alto - (2 * $margen)) / $maxValor;

        // IMPORTANTE: Definición estricta de XML para que funcione como imagen
        $svg = '<?xml version="1.0" encoding="UTF-8"?>';
        $svg .= '<svg width="' . $ancho . '" height="' . $alto . '" viewBox="0 0 ' . $ancho . ' ' . $alto . '" xmlns="http://www.w3.org/2000/svg">';
        
        // Fondo y Ejes
        $svg .= '<rect width="100%" height="100%" fill="#ffffff"/>'; // Fondo blanco explícito
        $yBase = $alto - $margen;
        $svg .= '<line x1="' . $margen . '" y1="' . $yBase . '" x2="' . ($ancho - $margen) . '" y2="' . $yBase . '" stroke="#666666" stroke-width="2" />';

        $xActual = $margen + ($espacioBarra / 2);

        foreach ($datos as $etiqueta => $valor) {
            $alturaBarra = $valor * $escalaY;
            $yPos = $yBase - $alturaBarra;
            
            // Barra (Azul)
            if ($valor > 0) {
                $svg .= '<rect x="' . $xActual . '" y="' . $yPos . '" width="' . $anchoRealBarra . '" height="' . $alturaBarra . '" fill="#007bff" />';
                
                // Texto Valor (encima de la barra)
                $xTextoValor = $xActual + ($anchoRealBarra / 2);
                $yTextoValor = $yPos - 5;
                $svg .= '<text x="' . $xTextoValor . '" y="' . $yTextoValor . '" font-family="Helvetica, Arial, sans-serif" font-size="12" fill="#000000" text-anchor="middle">' . $valor . '</text>';
            }

            // Texto Etiqueta (Mes)
            $xTextoEtiqueta = $xActual + ($anchoRealBarra / 2);
            $yTextoEtiqueta = $yBase + 15;
            $etiquetaCorta = substr($etiqueta, 0, 3);
            $svg .= '<text x="' . $xTextoEtiqueta . '" y="' . $yTextoEtiqueta . '" font-family="Helvetica, Arial, sans-serif" font-size="10" fill="#666666" text-anchor="middle">' . $etiquetaCorta . '</text>';

            $xActual += $anchoBarra;
        }

        $svg .= '</svg>';
        
        // AQUÍ ESTÁ EL TRUCO: Convertimos el SVG a Base64
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Generar PDF para reporte mensual
     */
    private function generarPDFMensual($stats, $sesiones, $nombreArchivo)
    {
        // Cálculos
        $ahorroTotal = floatval($stats['tiempo_total_horas']) * 650;
        $ahorroFormateado = number_format($ahorroTotal, 0, ',', '.');

        // Top Pruebas Syllabus
        $topPruebas = $sesiones->where('es_instruccion', true)
                               ->whereNotNull('codigo_prueba')
                               ->groupBy('codigo_prueba')
                               ->map(function($grupo) {
                                   return ['codigo' => $grupo->first()->codigo_prueba, 'cantidad' => $grupo->count()];
                               })->sortByDesc('cantidad')->take(5);

        // Gráfico
        $datosParaGrafico = [];
        $diasEnMes = Carbon::createFromDate($stats['año'], $stats['mes'], 1)->daysInMonth;
        for ($dia = 1; $dia <= $diasEnMes; $dia++) {
            $datosParaGrafico[(string)$dia] = $sesiones->filter(fn($s) => $s->fecha->day == $dia)->count();
        }
        $svgGrafico = $this->generarGraficoSVG($datosParaGrafico);

        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Reporte Mensual</title>
            <style>
                body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 20px; color: #333; font-size: 12px; }
                .header { text-align: center; border-bottom: 3px solid #003366; padding-bottom: 15px; margin-bottom: 25px; }
                .header h1 { color: #003366; margin: 0 0 5px 0; font-size: 22px; text-transform: uppercase; letter-spacing: 1px;}
                .header h2 { color: #666; margin: 0 0 5px 0; font-size: 14px; }
                .header p { color: #999; margin: 0; font-size: 10px; }
                
                .section-title { color: #003366; font-size: 14px; font-weight: bold; border-bottom: 2px solid #003366; padding-bottom: 4px; margin-bottom: 15px; margin-top: 25px; text-transform: uppercase; }
                
                /* Layout de DOMPDF con Tablas */
                .stats-table { width: 100%; border-collapse: separate; border-spacing: 8px; margin-bottom: 10px; margin-left: -8px;}
                .stats-table td { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; padding: 12px; text-align: center; width: 33.33%; }
                .stat-value { font-size: 20px; font-weight: bold; color: #003366; margin-bottom: 4px; }
                .stat-value.orange { color: #d97706; }
                .stat-value.green { color: #16a34a; }
                .stat-label { font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: bold; }

                /* Tablas de Datos */
                .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10px; }
                .data-table th { background-color: #003366; color: white; padding: 8px; text-align: left; font-weight: bold; }
                .data-table td { padding: 8px; border-bottom: 1px solid #e5e7eb; color: #374151; }
                .data-table tr:nth-child(even) td { background-color: #f9fafb; }
                
                .badge { background: #e0e7ff; color: #3730a3; padding: 3px 6px; border-radius: 4px; font-family: monospace; font-size: 9px; font-weight: bold; }
                .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 15px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Escuela de Aviación Naval</h1>
                <h2>Reporte Mensual Simulador PC-7 - {$stats['periodo']}</h2>
                <p>Documento Generado: " . now()->locale('es')->isoFormat('DD/MM/YYYY HH:mm') . " hrs</p>
            </div>

            <table class='stats-table'>
                <tr>
                    <td>
                        <div class='stat-value'>{$stats['alumnos_activos']}</div>
                        <div class='stat-label'>Alumnos Activos</div>
                    </td>
                    <td>
                        <div class='stat-value'>{$stats['total_sesiones']}</div>
                        <div class='stat-label'>Sesiones Totales</div>
                    </td>
                    <td>
                        <div class='stat-value'>{$stats['tiempo_total_horas']}h</div>
                        <div class='stat-label'>Horas Totales</div>
                    </td>
                </tr>
            </table>

            <table class='stats-table'>
                <tr>
                    <td>
                        <div class='stat-value orange'>{$stats['sesiones_instruccion']}</div>
                        <div class='stat-label'>Sesiones Oficiales</div>
                    </td>
                    <td>
                        <div class='stat-value orange'>{$stats['horas_instruccion']}h</div>
                        <div class='stat-label'>Horas de Instrucción</div>
                    </td>
                    <td>
                        <div class='stat-value green'>US$ {$ahorroFormateado}</div>
                        <div class='stat-label'>Ahorro Estimado</div>
                    </td>
                </tr>
            </table>

            <div class='section-title'>Actividad Diaria del Mes</div>
            <div style='text-align:center; margin-bottom: 20px;'>
                <img src='{$svgGrafico}' style='width: 100%; max-height: 250px;' />
            </div>

            <table width='100%' style='margin-bottom: 20px;'>
                <tr>
                    <td width='50%' style='vertical-align: top; padding-right: 10px;'>
                        <div class='section-title'>Top Pruebas Syllabus</div>
                        <table class='data-table'>
                            <tr><th>Código</th><th>Cantidad</th></tr>";
                            if($topPruebas->count() > 0) {
                                foreach ($topPruebas as $prueba) {
                                    $html .= "<tr><td><span class='badge'>{$prueba['codigo']}</span></td><td>{$prueba['cantidad']} veces</td></tr>";
                                }
                            } else {
                                $html .= "<tr><td colspan='2' style='text-align:center;'>Sin pruebas registradas</td></tr>";
                            }
            $html .= "  </table>
                    </td>
                    <td width='50%' style='vertical-align: top; padding-left: 10px;'>
                        <div class='section-title'>Métricas de Rendimiento</div>
                        <table class='data-table'>
                            <tr><td>Promedio Diario</td><td><strong>{$stats['promedio_diario']} sesiones/día</strong></td></tr>
                            <tr><td>Duración Promedio</td><td><strong>{$stats['tiempo_promedio']} min/sesión</strong></td></tr>
                            <tr><td>Efectividad (Fin/Total)</td><td><strong>{$stats['sesiones_finalizadas']} de {$stats['total_sesiones']}</strong></td></tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div class='section-title'>Detalle de Vuelos del Mes</div>
            <table class='data-table'>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno</th>
                        <th>NPI</th>
                        <th>Prueba/Actividad</th>
                        <th>Duración</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($sesiones->sortByDesc('fecha') as $sesion) {
            $duracion = $sesion->duracion_minutos ? $sesion->duracion_minutos . ' min' : '-';
            $actividad = $sesion->actividad;
            if ($sesion->codigo_prueba) {
                $actividad = "<span class='badge'>" . $sesion->codigo_prueba . "</span> " . $actividad;
            }
            
            $html .= "
                    <tr>
                        <td style='white-space: nowrap;'>" . $sesion->fecha->format('d/m/Y') . "</td>
                        <td>" . ($sesion->alumno->nombre_completo ?? 'N/A') . "</td>
                        <td>" . ($sesion->alumno->npi ?? 'N/A') . "</td>
                        <td>{$actividad}</td>
                        <td>{$duracion}</td>
                    </tr>";
        }

        $html .= "
                </tbody>
            </table>

            <div class='footer'>
                <p><strong>Armada de Chile - Escuela de Aviación Naval</strong><br>Sistema Automático de Gestión de Simulador PC-7</p>
            </div>
        </body>
        </html>";
        
        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download($nombreArchivo . '.pdf');
    }


    /**
     * Generar PDF para reporte anual
     */
    private function generarPDFAnual($stats, $sesiones, $nombreArchivo)
    {
        $ahorroTotal = floatval($stats['tiempo_total_horas']) * 650;
        $ahorroFormateado = number_format($ahorroTotal, 0, ',', '.');

        // Top Alumnos
        $topAlumnos = $sesiones->groupBy('alumno_id')->map(function($g) {
            return [
                'nombre' => $g->first()->alumno->nombre_completo ?? 'N/A',
                'npi' => $g->first()->alumno->npi ?? 'N/A',
                'sesiones' => $g->count(),
                'tiempo' => round($g->where('estado', 'finalizada')->sum('duracion_minutos') / 60, 1)
            ];
        })->sortByDesc('sesiones')->take(5)->values();

        // Top Pruebas
        $topPruebas = $sesiones->where('es_instruccion', true)->whereNotNull('codigo_prueba')->groupBy('codigo_prueba')->map(function($g) {
            return ['codigo' => $g->first()->codigo_prueba, 'cantidad' => $g->count()];
        })->sortByDesc('cantidad')->take(5);

        // Gráfico Mensual
        $meses = [1=>'Ene', 2=>'Feb', 3=>'Mar', 4=>'Abr', 5=>'May', 6=>'Jun', 7=>'Jul', 8=>'Ago', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dic'];
        $datosParaGrafico = [];
        foreach ($meses as $num => $nom) {
            $datosParaGrafico[$nom] = $sesiones->filter(fn($s) => $s->fecha->month == $num)->count();
        }
        $svgGrafico = $this->generarGraficoSVG($datosParaGrafico);

        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Reporte Anual</title>
            <style>
                body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 20px; color: #333; font-size: 12px; }
                .header { text-align: center; border-bottom: 3px solid #003366; padding-bottom: 15px; margin-bottom: 25px; }
                .header h1 { color: #003366; margin: 0 0 5px 0; font-size: 22px; text-transform: uppercase; letter-spacing: 1px;}
                .header h2 { color: #666; margin: 0 0 5px 0; font-size: 14px; }
                .header p { color: #999; margin: 0; font-size: 10px; }
                
                .section-title { color: #003366; font-size: 14px; font-weight: bold; border-bottom: 2px solid #003366; padding-bottom: 4px; margin-bottom: 15px; margin-top: 25px; text-transform: uppercase; }
                
                .stats-table { width: 100%; border-collapse: separate; border-spacing: 8px; margin-bottom: 10px; margin-left: -8px;}
                .stats-table td { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; padding: 12px; text-align: center; width: 33.33%; }
                .stat-value { font-size: 20px; font-weight: bold; color: #003366; margin-bottom: 4px; }
                .stat-value.orange { color: #d97706; }
                .stat-value.green { color: #16a34a; }
                .stat-label { font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: bold; }

                .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10px; }
                .data-table th { background-color: #003366; color: white; padding: 8px; text-align: left; font-weight: bold; }
                .data-table td { padding: 8px; border-bottom: 1px solid #e5e7eb; color: #374151; }
                .data-table tr:nth-child(even) td { background-color: #f9fafb; }
                
                .badge { background: #e0e7ff; color: #3730a3; padding: 3px 6px; border-radius: 4px; font-family: monospace; font-size: 9px; font-weight: bold; }
                .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 15px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Escuela de Aviación Naval</h1>
                <h2>Reporte Anual Simulador PC-7 - Año {$stats['año']}</h2>
                <p>Documento Generado: " . now()->locale('es')->isoFormat('DD/MM/YYYY HH:mm') . " hrs</p>
            </div>

            <table class='stats-table'>
                <tr>
                    <td>
                        <div class='stat-value'>{$stats['alumnos_activos']}</div>
                        <div class='stat-label'>Alumnos Totales</div>
                    </td>
                    <td>
                        <div class='stat-value'>{$stats['total_sesiones']}</div>
                        <div class='stat-label'>Sesiones Anuales</div>
                    </td>
                    <td>
                        <div class='stat-value'>{$stats['tiempo_total_horas']}h</div>
                        <div class='stat-label'>Horas de Vuelo</div>
                    </td>
                </tr>
            </table>

            <table class='stats-table'>
                <tr>
                    <td>
                        <div class='stat-value orange'>{$stats['sesiones_instruccion']}</div>
                        <div class='stat-label'>Sesiones Oficiales</div>
                    </td>
                    <td>
                        <div class='stat-value orange'>{$stats['horas_instruccion']}h</div>
                        <div class='stat-label'>Horas de Instrucción</div>
                    </td>
                    <td>
                        <div class='stat-value green'>US$ {$ahorroFormateado}</div>
                        <div class='stat-label'>Ahorro Anual Estimado</div>
                    </td>
                </tr>
            </table>

            <div class='section-title'>Curva de Actividad Mensual</div>
            <div style='text-align:center; margin-bottom: 20px;'>
                <img src='{$svgGrafico}' style='width: 100%; max-height: 250px;' />
            </div>

            <table width='100%' style='margin-bottom: 20px;'>
                <tr>
                    <td width='50%' style='vertical-align: top; padding-right: 10px;'>
                        <div class='section-title'>Top 5 Alumnos del Año</div>
                        <table class='data-table'>
                            <tr><th>Pos</th><th>Alumno / NPI</th><th>Horas</th></tr>";
                            foreach ($topAlumnos as $index => $alumno) {
                                $pos = $index + 1;
                                $html .= "<tr><td>{$pos}</td><td><strong>{$alumno['nombre']}</strong><br><span style='color:#666;font-size:9px;'>NPI: {$alumno['npi']}</span></td><td>{$alumno['tiempo']}h</td></tr>";
                            }
            $html .= "  </table>
                    </td>
                    <td width='50%' style='vertical-align: top; padding-left: 10px;'>
                        <div class='section-title'>Top Pruebas Syllabus</div>
                        <table class='data-table'>
                            <tr><th>Código</th><th>Ejecuciones</th></tr>";
                            if($topPruebas->count() > 0) {
                                foreach ($topPruebas as $prueba) {
                                    $html .= "<tr><td><span class='badge'>{$prueba['codigo']}</span></td><td>{$prueba['cantidad']} veces</td></tr>";
                                }
                            } else {
                                $html .= "<tr><td colspan='2' style='text-align:center;'>Sin pruebas registradas</td></tr>";
                            }
            $html .= "  </table>
                    </td>
                </tr>
            </table>

            <div class='footer'>
                <p><strong>Armada de Chile - Escuela de Aviación Naval</strong><br>Sistema Automático de Gestión de Simulador PC-7</p>
            </div>
        </body>
        </html>";
        
        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download($nombreArchivo . '.pdf');
    }

    
}