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
        // 1. Total Histórico de Sesiones (Todas las que existen)
        $totalSesiones = Sesion::count();

        // 2. Tiempo Promedio Global (De todas las sesiones finalizadas históricamente)
        $tiempoPromedio = Sesion::where('estado', 'finalizada')
                                ->avg('duracion_minutos');

        // 3. Horas Totales Históricas (Suma de todos los minutos / 60)
        $totalMinutos = Sesion::where('estado', 'finalizada')
                              ->sum('duracion_minutos');
        
        $horasTotales = $totalMinutos > 0 ? round($totalMinutos / 60, 1) : 0;

        // 4. Ahorro Total Histórico (Horas * 650 USD)
        // Usamos las horas totales calculadas arriba
        $ahorroTotal = $horasTotales * 650;

        $data = [
            'total_historico_sesiones' => number_format($totalSesiones, 0, ',', '.'),
            'tiempo_promedio_global'   => round($tiempoPromedio ?? 0, 0), // Redondeamos a minutos enteros
            'horas_totales_global'     => number_format($horasTotales, 1, ',', '.'), // 1 decimal (ej: 150.5 h)
            'ahorro_total_global'      => number_format($ahorroTotal, 0, ',', '.'), // Formato dinero (ej: 1.500.000)
            
            // Mantenemos estos por si los usas en otro lado, pero los globales son los de arriba
            'sesiones_hoy' => Sesion::whereDate('fecha', today())->count(),
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
    /**
     * Generar PDF para reporte mensual
     */
    private function generarPDFMensual($stats, $sesiones, $nombreArchivo)
    {
        // --- 1. CÁLCULO DE AHORRO (Igual que en el anual) ---
        $minutosTotales = $sesiones->where('estado', 'finalizada')->sum('duracion_minutos');
        $horasReales = $minutosTotales / 60;
        $ahorroTotal = $horasReales * 650; // Regla: 1 hora = 650 USD
        $ahorroFormateado = number_format($ahorroTotal, 0, ',', '.');

        // --- 2. PREPARACIÓN DE DATOS PARA GRÁFICO (Día por día) ---
        $datosParaGrafico = [];
        
        // Creamos una fecha base para saber cuántos días tiene este mes específico
        $fechaBase = Carbon::createFromDate($stats['año'], $stats['mes'], 1);
        $diasEnMes = $fechaBase->daysInMonth;

        // Iteramos del día 1 al último día del mes
        for ($dia = 1; $dia <= $diasEnMes; $dia++) {
            // Filtramos las sesiones de ese día específico
            $cantidad = $sesiones->filter(function ($sesion) use ($dia) {
                return $sesion->fecha->day == $dia;
            })->count();

            // La etiqueta será el número del día (ej: "1", "15", "30")
            $datosParaGrafico[(string)$dia] = $cantidad;
        }

        // Generamos el SVG Base64
        $svgGrafico = $this->generarGraficoSVG($datosParaGrafico);


        // --- 3. CONSTRUCCIÓN DEL HTML ---
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Reporte Mensual - {$stats['periodo']}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; color: #333; font-size: 12px; }
                .header { text-align: center; border-bottom: 3px solid #004080; padding-bottom: 20px; margin-bottom: 30px; }
                .section-title { font-size: 16px; font-weight: bold; color: #333; border-bottom: 2px solid #004080; padding-bottom: 8px; margin-bottom: 15px; }
                
                /* Cajas de Estadísticas (Estilo mejorado) */
                .stats { display: flex; justify-content: space-around; margin: 20px 0; }
                .stat-box { text-align: center; border: 1px solid #ddd; padding: 15px; border-radius: 5px; min-width: 120px; }
                .stat-number { font-size: 24px; font-weight: bold; color: #004080; }
                .stat-label { font-size: 11px; color: #666; margin-top: 5px; }

                /* Contenedor del Gráfico */
                .chart-container { 
                    text-align: center; 
                    margin: 20px 0; 
                    padding: 10px;
                }

                /* Tabla de Detalle */
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
                th { background-color: #f0f0f0; font-weight: bold; }
                
                .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 15px; }
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
                    <div class='stat-number'>{$stats['alumnos_activos']}</div>
                    <div class='stat-label'>Alumnos Activos</div>
                </div>
                <div class='stat-box'>
                    <div class='stat-number'>{$stats['total_sesiones']}</div>
                    <div class='stat-label'>Total Sesiones</div>
                </div>
                <div class='stat-box'>
                    <div class='stat-number'>{$stats['tiempo_total_horas']}h</div>
                    <div class='stat-label'>Tiempo Total</div>
                </div>
                <div class='stat-box'>
                    <div class='stat-number' style='color: #28a745;'>US$ {$ahorroFormateado}</div>
                    <div class='stat-label'>Ahorro Operativo Est.</div>
                </div>
            </div>

            <div class='section'>
                <div class='section-title'>Actividad Diaria del Mes</div>
                <div class='chart-container'>
                    <img src='{$svgGrafico}' style='width: 100%; max-height: 300px;' />
                </div>
                <p style='text-align:center; font-size:10px; color:#666;'>* Gráfico de sesiones por día</p>
            </div>

            <br>
            <br>

            <div class='section'>
                <div class='section-title'>Detalle de Sesiones</div>
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
            </div>

            <div class='footer'>
                <p>Armada de Chile - Escuela de Aviación Naval</p>
                <p>Sistema de Gestión Simulador PC-7</p>
            </div>
        </body>
        </html>";
        
        $pdf = PDF::loadHTML($html)
             ->setPaper('a4', 'portrait');

        return $pdf->download($nombreArchivo . '.pdf');
    }


    /**
     * Generar PDF para reporte anual
     */
    private function generarPDFAnual($stats, $sesiones, $nombreArchivo)
    {
        // --- PREPARACIÓN DE DATOS ---
        $sesionesPorMes = [];
        // Array simple para el generador de gráficos: ['Ene' => 15, 'Feb' => 20...]
        $datosParaGrafico = []; 

        // ... dentro de generarPDFAnual ...

        // 1. Calculamos los minutos totales de sesiones finalizadas
        $minutosTotales = $sesiones->where('estado', 'finalizada')->sum('duracion_minutos');

        // 2. Convertimos a horas (mantenemos los decimales para que el cálculo de dinero sea exacto)
        $horasReales = $minutosTotales / 60;

        // 3. Aplicamos la regla de negocio: 1 hora = 650 USD
        $ahorroTotal = $horasReales * 650;

        // 4. Formateamos el dinero para que se vea bonito (Ej: 12.500)
        // Usamos '.' como separador de miles y ',' para decimales (formato chileno/europeo)
        // El '0' indica que no queremos decimales en el dinero (números enteros)
        $ahorroFormateado = number_format($ahorroTotal, 0, ',', '.');
        
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        foreach ($meses as $numeroMes => $nombreMes) {
            $sesionesMes = $sesiones->filter(function($sesion) use ($numeroMes) {
                return $sesion->fecha->month == $numeroMes;
            });
            
            $cantidad = $sesionesMes->count();
            
            // Llenamos datos para tabla
            $sesionesPorMes[] = [
                'mes' => $nombreMes,
                'sesiones' => $cantidad,
                'finalizadas' => $sesionesMes->where('estado', 'finalizada')->count(),
                'tiempo_total' => round($sesionesMes->where('estado', 'finalizada')->sum('duracion_minutos') / 60, 1),
                'alumnos' => $sesionesMes->unique('alumno_id')->count()
            ];

            // Llenamos datos para el gráfico
            $datosParaGrafico[$nombreMes] = $cantidad;
        }

        // --- GENERACIÓN DEL GRÁFICO OFFLINE ---
        // Aquí ocurre la magia. Obtenemos el string SVG.
        $svgGrafico = $this->generarGraficoSVG($datosParaGrafico);

        // Top 5 alumnos (Tu código original)
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
            ->values();

        // --- CONSTRUCCIÓN DEL HTML ---
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Reporte Anual - {$stats['año']}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; color: #333; font-size: 12px; }
                .header { text-align: center; border-bottom: 3px solid #004080; padding-bottom: 20px; margin-bottom: 30px; }
                .section-title { font-size: 16px; font-weight: bold; color: #333; border-bottom: 2px solid #004080; padding-bottom: 8px; margin-bottom: 15px; }
                
                /* Estilos de tus cajas de estadísticas... */
                .stats { display: flex; justify-content: space-around; margin: 20px 0; }
                .stat-box { text-align: center; border: 1px solid #ddd; padding: 15px; border-radius: 5px; min-width: 120px; }
                .stat-number { font-size: 24px; font-weight: bold; color: #004080; }
                .stat-label { font-size: 11px; color: #666; margin-top: 5px; }

                /* Contenedor del Gráfico */
                .chart-container { 
                    text-align: center; 
                    margin: 20px 0; 
                    padding: 10px;
                }
                
                /* Tablas y grids... */
                .monthly-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin: 15px 0; }
                .month-card { border: 1px solid #ddd; padding: 10px; border-radius: 5px; background: #f9f9f9; }
                table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
                th { background-color: #f0f0f0; font-weight: bold; }
                .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 15px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Reporte Anual del Simulador PC-7</h1>
                <h2>Año {$stats['año']}</h2>
                <p>Generado el " . now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY HH:mm') . "</p>
            </div>

            <div class='stats'>
                <div class='stat-box'>
                    <div class='stat-number'>{$stats['alumnos_activos']}</div>
                    <div class='stat-label'>Alumnos</div>
                </div>
                <div class='stat-box'>
                    <div class='stat-number'>{$stats['total_sesiones']}</div>
                    <div class='stat-label'>Total Sesiones</div>
                </div>
                <div class='stat-box'>
                    <div class='stat-number'>{$stats['tiempo_total_horas']}h</div>
                    <div class='stat-label'>Tiempo Total</div>
                </div>
                <div class='stat-box'>
                    <div class='stat-number' style='color: #28a745;'>US$ {$ahorroFormateado}</div>
                    <div class='stat-label'>Ahorro Operativo Est.</div>
                </div>
            </div>

            <div class='section'>
                <div class='section-title'>Actividad Mensual</div>
                <div class='chart-container'>
                    <img src='{$svgGrafico}' style='width: 100%; max-height: 300px;' />
                </div>
                <p style='text-align:center; font-size:10px; color:#666;'>* Gráfico generado localmente</p>
            </div>

            <br>
            <br>

            <div class='section'>
                <div class='section-title'>Detalle Mensual</div>
                <div class='monthly-grid'>";
                
                // Nota: Tu estilo CSS de grid puede no funcionar perfecto en versiones viejas de DOMPDF.
                // Si ves que se desordena, usa una tabla clásica HTML.
                // Aquí dejo una versión simplificada usando tabla flotante inline para compatibilidad
                $html .= "<table style='border:none;'><tr>";
                $counter = 0;
                foreach ($sesionesPorMes as $mes) {
                    if($counter > 0 && $counter % 3 == 0) $html .= "</tr><tr>";
                    $html .= "
                        <td style='border:none; padding:5px; width:33%;'>
                            <div class='month-card'>
                                <div style='color:#004080; font-weight:bold;'>{$mes['mes']}</div>
                                <div>Sesiones: {$mes['sesiones']}</div>
                                <div>Horas: {$mes['tiempo_total']}</div>
                            </div>
                        </td>";
                    $counter++;
                }
                $html .= "</tr></table>";

        $html .= "
                </div>
            </div>

            <div class='section'>
                <div class='section-title'>Top 5 Alumnos</div>
                <table>
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th>Alumno</th>
                            <th>NPI</th>
                            <th>Sesiones</th>
                            <th>Horas</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($topAlumnos as $index => $alumno) {
            $html .= "<tr>
                        <td style='text-align:center;'>".($index+1)."</td>
                        <td>{$alumno['nombre']}</td>
                        <td>{$alumno['npi']}</td>
                        <td style='text-align:center;'>{$alumno['sesiones']}</td>
                        <td style='text-align:center;'>{$alumno['tiempo']}</td>
                      </tr>";
        }

        $html .= "
                    </tbody>
                </table>
            </div>

            <div class='footer'>
                <p>Armada de Chile - Escuela de Aviación Naval</p>
                <p>Sistema de Gestión Simulador PC-7</p>
            </div>
        </body>
        </html>";

        $pdf = PDF::loadHTML($html)
            ->setPaper('a4', 'portrait'); // Ya no necesitas 'isRemoteEnabled'
        
        return $pdf->download($nombreArchivo . '.pdf');
    }

    
}