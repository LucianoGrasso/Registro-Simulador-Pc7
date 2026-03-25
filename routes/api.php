<?php

use App\Events\TelemetryUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RelayController;

Route::post('/telemetry', function (Request $request) {
    // Usamos dispatch para que no bloquee la respuesta HTTP
    TelemetryUpdated::dispatch($request->all()); 
    
    return response()->json(['status' => 'Data received'], 200);
});

Route::get('/relay/status', [RelayController::class, 'status']);
Route::post('/relay/toggle', [RelayController::class, 'toggle']);