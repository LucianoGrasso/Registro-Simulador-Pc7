<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RelayController extends Controller
{
    public function toggle(Request $request) {
        // Intentamos obtener 'active' del request normal o del JSON crudo (para Beacon)
        $activate = $request->input('active');
        
        if ($request->isMethod('post') && empty($activate)) {
            $json = json_decode($request->getContent(), true);
            $activate = $json['active'] ?? false;
        }

        $scriptPath = "/var/www/html/telemetry_bridge/xplane_relay.py";

        if ($activate) {
            shell_exec("pkill -f xplane_relay.py");
            shell_exec("/usr/bin/python3 $scriptPath > /var/www/html/storage/logs/python_bridge.log 2>&1 &");
            return response()->json(['status' => 'started'], 200);
        } else {
            // Comando para matar el proceso
            shell_exec("pkill -f xplane_relay.py");
            return response()->json(['status' => 'stopped'], 200);
        }
    }

    public function status() {
        // Ejecutamos un comando para contar cuántos procesos del script hay activos
        // 'pgrep -f' busca el nombre del archivo en la lista de procesos
        $process = shell_exec("pgrep -f xplane_relay.py");
        
        return response()->json([
            'active' => !empty(trim($process))
        ], 200);
    }
}