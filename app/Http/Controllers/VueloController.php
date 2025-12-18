<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Sesion;

class VueloController extends Controller
{
    // Listado de archivos
    public function index()
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

            // --- ESTRATEGIA 1: INTENTAR EXTRAER FECHA DEL NOMBRE (vuelo_YYYYMMDD_HHMMSS.json) ---
            if (preg_match('/vuelo_(\d{8})_(\d{6})/i', $filename, $matches)) {
                try {
                    $fechaStr = $parts[1] . $parts[2];
                    $fechaObj = Carbon::createFromFormat('YmdHis', $fechaStr);
                    $fechaBonita = $fechaObj->format('d/m/Y H:i');
                    $timestamp = $fechaObj->timestamp;
                } catch (\Exception $e) {
                    // Si falla el formato, pasamos a la estrategia 2
                }
            } 
            
            // --- ESTRATEGIA 2: SI NO HAY FECHA EN EL NOMBRE, USAR LA DEL ARCHIVO FÍSICO ---
            if ($timestamp === 0) {
                $timestamp = $file->getMTime();
                $fechaBonita = Carbon::createFromTimestamp($timestamp)->format('d/m/Y H:i');
            }

            // Buscar si hay sesión asociada
            $alumno = 'Sin Asignar';
            $sesion = $sesiones->get($filename);
            
            if ($sesion && $sesion->alumno) {
                $alumno = $sesion->alumno->nombre_completo;
                // Opcional: Si encontramos la sesión, podemos usar la fecha real de la sesión en vez del archivo
                // $timestamp = $sesion->hora_inicio->timestamp; 
                // $fechaBonita = $sesion->hora_inicio->format('d/m/Y H:i');
            }

            $vuelos[] = [
                'archivo' => $filename,
                'fecha' => $fechaBonita,
                'timestamp' => $timestamp,
                'size' => number_format($file->getSize() / 1048576, 2) . ' MB', // Convertir a MB
                'alumno' => $alumno
            ];
        }

        // 3. ORDENAR: El más reciente primero (Timestamp mayor va primero)
        usort($vuelos, function ($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        // --- NUEVO: Capturamos el nombre del archivo más reciente ---
        // Como ya ordenamos, el índice 0 es el más nuevo.
        $archivoMasReciente = !empty($vuelos) ? $vuelos[0]['archivo'] : null;

        // Pasamos esa variable extra a la vista
        return view('vuelos.index', compact('vuelos', 'archivoMasReciente'));
    }

    // Ver mapa (MODIFICADO para conectar con la Base de Datos)
    public function show($archivo)
    {
        // 1. Verificamos que el archivo físico exista
        if (!file_exists(public_path('vuelos/' . $archivo))) {
            abort(404, 'El archivo de vuelo no existe en el disco.');
        }

        // 2. BUSCAMOS LA SESIÓN EN LA BASE DE DATOS
        $sesion = Sesion::where('archivo_vuelo', $archivo)
                        ->with('alumno') 
                        ->first();

        // 3. Enviamos todo a la vista
        return view('vuelos.show', [
            'archivoJson' => $archivo,
            'sesion' => $sesion 
        ]);
    }
}