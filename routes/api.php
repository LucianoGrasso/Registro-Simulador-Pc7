<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Events\TelemetryUpdated;

// Esta es la ruta que usará el Python: http://tu-ip/api/telemetry
Route::post('/telemetry', function (Request $request) {
    // Tomamos todo lo que mandó el Python y lo lanzamos al WebSocket (Reverb)
    broadcast(new TelemetryUpdated($request->all()))->toOthers();

    return response()->json(['status' => 'Data broadcasted']);
});