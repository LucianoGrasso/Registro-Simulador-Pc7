<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Sesion; // <--- AGREGADO: Importante para buscar los datos del alumno

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

            $parts = explode('_', str_replace('.json', '', $filename));
            
            if (count($parts) >= 3) {
                try {
                    $fechaStr = $parts[1] . $parts[2];
                    $fechaObj = Carbon::createFromFormat('YmdHis', $fechaStr);
                    $fechaBonita = $fechaObj->format('d/m/Y H:i');
                    $timestamp = $fechaObj->timestamp;
                } catch (\Exception $e) {
                    $fechaBonita = 'Fecha desconocida';
                    $timestamp = 0;
                }
            } else {
                $fechaBonita = 'Sin fecha';
                $timestamp = $file->getMTime();
            }

            // Buscar si hay sesión asociada
            $alumno = 'Sin Asignar';
            $sesion = $sesiones->get($filename);
            
            if ($sesion && $sesion->alumno) {
                $alumno = $sesion->alumno->nombre_completo;
            }

            $vuelos[] = [
                'archivo' => $filename,
                'fecha' => $fechaBonita,
                'timestamp' => $timestamp,
                'size' => number_format($file->getSize() / 1048576, 2) . ' MB', // Convertir a MB
                'alumno' => $alumno
            ];
        }

        usort($vuelos, function ($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return view('vuelos.index', compact('vuelos'));
    }

    // Ver mapa (MODIFICADO para conectar con la Base de Datos)
    public function show($archivo)
    {
        // 1. Verificamos que el archivo físico exista
        if (!file_exists(public_path('vuelos/' . $archivo))) {
            abort(404, 'El archivo de vuelo no existe en el disco.');
        }

        // 2. BUSCAMOS LA SESIÓN EN LA BASE DE DATOS
        // Esto busca qué sesión tiene guardado este nombre de archivo
        // y trae también los datos del alumno asociado.
        $sesion = Sesion::where('archivo_vuelo', $archivo)
                        ->with('alumno') 
                        ->first();

        // 3. Enviamos todo a la vista
        // Si encontró la sesión, $sesion tendrá datos. Si no (archivo viejo), será null.
        return view('vuelos.show', [
            'archivoJson' => $archivo,
            'sesion' => $sesion 
        ]);
    }
}