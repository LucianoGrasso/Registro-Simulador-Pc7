<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Sesion;
// Importante para la paginación manual de arrays
use Illuminate\Pagination\LengthAwarePaginator;

class VueloController extends Controller
{
    // Listado de archivos con Filtros y Paginación
    public function index(Request $request)
    {
        $path = public_path('vuelos');
        
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $files = File::files($path);
        
        // 1. Obtener todos los nombres de archivo para hacer una sola consulta
        $filenames = array_map(function($file) {
            return $file->getFilename();
        }, $files);

        // 2. Buscar todas las sesiones que coincidan con estos archivos
        $sesiones = Sesion::whereIn('archivo_vuelo', $filenames)
                          ->with('alumno')
                          ->get()
                          ->keyBy('archivo_vuelo');

        $vuelos = [];

        foreach ($files as $file) {
            $filename = $file->getFilename();
            if ($file->getExtension() !== 'json') continue;

            $timestamp = 0;
            $fechaBonita = 'Desconocida';
            $fechaObjeto = null; // Necesario para filtrar por rango de fechas

            // --- ESTRATEGIA 1: EXTRAER FECHA DEL NOMBRE ---
            if (preg_match('/vuelo_(\d{8})_(\d{6})/i', $filename, $matches)) {
                try {
                    // CORRECCIÓN: Usamos $matches, no $parts
                    $fechaStr = $matches[1] . $matches[2];
                    $fechaObjeto = Carbon::createFromFormat('YmdHis', $fechaStr);
                } catch (\Exception $e) {
                    // Si falla, quedará null y pasará a estrategia 2
                }
            } 
            
            // --- ESTRATEGIA 2: FECHA DEL ARCHIVO FÍSICO ---
            if (!$fechaObjeto) {
                $timestamp = $file->getMTime();
                $fechaObjeto = Carbon::createFromTimestamp($timestamp);
            }

            // Formateamos los datos finales
            $timestamp = $fechaObjeto->timestamp;
            $fechaBonita = $fechaObjeto->format('d/m/Y H:i');

            // Buscar datos del alumno
            $alumno = 'Sin Asignar';
            $npi = ''; // Agregamos NPI para el buscador
            
            $sesion = $sesiones->get($filename);
            
            if ($sesion && $sesion->alumno) {
                $alumno = $sesion->alumno->nombre_completo;
                $npi = $sesion->alumno->npi;
            }

            $vuelos[] = [
                'archivo' => $filename,
                'fecha_texto' => $fechaBonita,
                'fecha_obj' => $fechaObjeto,
                'timestamp' => $timestamp,
                'size' => number_format($file->getSize() / 1048576, 2) . ' MB',
                'alumno' => $alumno,
                'npi' => $npi
            ];
        }

        // --- 3. FILTRADO ---
        $vuelosCollection = collect($vuelos);

        // A. Filtro por Buscador (Texto)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $vuelosCollection = $vuelosCollection->filter(function ($vuelo) use ($search) {
                return str_contains(strtolower($vuelo['alumno']), $search) ||
                       str_contains(strtolower($vuelo['npi']), $search) ||
                       str_contains(strtolower($vuelo['archivo']), $search);
            });
        }

        // B. Filtro por Fecha Inicio
        if ($request->filled('fecha_inicio')) {
            $inicio = Carbon::parse($request->fecha_inicio)->startOfDay();
            $vuelosCollection = $vuelosCollection->filter(function ($vuelo) use ($inicio) {
                return $vuelo['fecha_obj'] && $vuelo['fecha_obj']->gte($inicio);
            });
        }

        // C. Filtro por Fecha Fin
        if ($request->filled('fecha_fin')) {
            $fin = Carbon::parse($request->fecha_fin)->endOfDay();
            $vuelosCollection = $vuelosCollection->filter(function ($vuelo) use ($fin) {
                return $vuelo['fecha_obj'] && $vuelo['fecha_obj']->lte($fin);
            });
        }

        // Ordenamos: El más reciente primero
        $vuelosAll = $vuelosCollection->sortByDesc('timestamp')->values()->all();

        // Capturamos el más reciente (del total filtrado) para la etiqueta "NUEVO"
        $archivoMasReciente = !empty($vuelosAll) ? $vuelosAll[0]['archivo'] : null;


        // --- 4. PAGINACIÓN MANUAL ---
        $perPage = 10; // Cantidad de vuelos por página
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        
        // Cortamos el array para obtener solo los items de esta página
        $currentItems = array_slice($vuelosAll, ($currentPage - 1) * $perPage, $perPage);

        // Creamos el objeto paginador
        $vuelos = new LengthAwarePaginator(
            $currentItems, 
            count($vuelosAll), // Total de items
            $perPage, 
            $currentPage, 
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $request->query() // Mantiene los filtros en los links de paginación
            ]
        );

        return view('vuelos.index', compact('vuelos', 'archivoMasReciente'));
    }

    // Ver mapa y Reproductor de Vuelo
    public function show($archivo)
    {
        // 1. Construir ruta y verificar existencia
        $rutaCompleta = public_path('vuelos/' . $archivo);

        if (!file_exists($rutaCompleta)) {
            return redirect()->route('vuelos.index')->with('error', 'El archivo de vuelo no existe en el disco.');
        }

        // 2. Leer y decodificar JSON
        $jsonContent = file_get_contents($rutaCompleta);
        $flightData = json_decode($jsonContent, true);

        // 3. CALCULAR ESTADÍSTICAS DE TELEMETRÍA 📊
        $coleccion = collect($flightData);

        $stats = [
            // Actitud (Nuevos datos del script Python)
            'pitch_max' => $coleccion->max('pitch') ?? 0, // Nariz arriba máx
            'pitch_min' => $coleccion->min('pitch') ?? 0, // Nariz abajo máx
            'roll_max'  => $coleccion->max('roll') ?? 0,  // Inclinación derecha máx
            'roll_min'  => $coleccion->min('roll') ?? 0,  // Inclinación izquierda máx
            
            // Desempeño
            'alt_max'   => $coleccion->max('alt') ?? 0,   // Altitud máxima
            'gs_max'    => $coleccion->max('spd') ?? 0,   // Velocidad máxima (Nota: Python usa 'spd')
        ];

        // 4. Buscar datos de la sesión (Alumno)
        $sesion = Sesion::where('archivo_vuelo', $archivo)
                        ->with('alumno') 
                        ->first();

        // 5. Enviar a la vista
        return view('vuelos.show', [
            'archivoNombre' => $archivo, 
            'flightData' => $flightData, // Datos crudos para el mapa JS
            'sesion' => $sesion,         // Info del alumno
            'stats' => $stats            // Estadísticas calculadas
        ]);
    }
}