<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class IDUController extends Controller
{
    public function index()
    {
        // ACTUALIZACIÓN: Ruta con la subcarpeta exacta
        $scriptName = 'telemetry_bridge/xplane_relay.py';
        $scriptPath = base_path($scriptName);

        // Verificamos si existe (para evitar errores de PHP)
        if (!file_exists($scriptPath)) {
            \Log::error("IDU: No se encontró el script en: " . $scriptPath);
            return Inertia::render('Welcome');
        }

        // Buscamos si ya está corriendo
        $isRunning = shell_exec("pgrep -f $scriptName");

        if (!$isRunning) {
            // Ejecutamos en segundo plano
            shell_exec("python3 $scriptPath > /dev/null 2>&1 &");
        }

        return Inertia::render('Welcome');
    }
}