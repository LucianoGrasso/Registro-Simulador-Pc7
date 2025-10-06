<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SoporteController;
use Illuminate\Support\Facades\Route;

// Página de bienvenida (redirige al login si no está autenticado)
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Dashboard principal (redirige según rol)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rutas que requieren autenticación
Route::middleware('auth')->group(function () {
    
    // ===== RUTAS PARA TODOS LOS USUARIOS AUTENTICADOS =====
    
    // Scanner QR (página principal para operadores)
    Route::get('/scanner', [SesionController::class, 'scanner'])->name('sesiones.scanner');
    Route::post('/scanner/procesar', [SesionController::class, 'procesarQR'])->name('sesiones.procesar-qr');

    Route::post('/sesiones/{id}/finalizar-directa', [SesionController::class, 'finalizarSesionDirecta'])
    ->name('sesiones.finalizar-directa');
    
    // Sesiones activas (todos pueden ver)
    Route::get('/sesiones/activas', [SesionController::class, 'activas'])->name('sesiones.activas');
    Route::get('/sesiones/activas-ajax', [SesionController::class, 'activasAjax'])->name('sesiones.activas-ajax');
    
    // Dashboard datos actualizados (AJAX)
    Route::get('/dashboard/datos', [DashboardController::class, 'datosActualizados'])->name('dashboard.datos');
    
    // Información del operador (reemplaza profile para operadores)
    Route::get('/mi-informacion', function () {
        return view('operador.info');
    })->name('operador.info');
    
    // Perfil de usuario (solo para administradores)
    Route::middleware('admin')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('/soporte/crear', [SoporteController::class, 'create'])->name('soporte.create');
    Route::post('/soporte', [SoporteController::class, 'store'])->name('soporte.store');
    Route::get('/mis-tickets', [SoporteController::class, 'misTickets'])->name('soporte.mis-tickets');
    
    // ===== RUTAS SOLO PARA ADMINISTRADORES =====
    
    Route::middleware('admin')->group(function () {
        
        // CRUD completo de alumnos
        Route::resource('alumnos', AlumnoController::class);
        
        // Rutas adicionales para alumnos
        Route::post('/alumnos/{id}/regenerar-qr', [AlumnoController::class, 'regenerarQR'])->name('alumnos.regenerar-qr');
        Route::get('/alumnos/{id}/descargar-qr', [AlumnoController::class, 'descargarQR'])->name('alumnos.descargar-qr');
        Route::patch('/alumnos/{id}/toggle-estado', [AlumnoController::class, 'toggleEstado'])->name('alumnos.toggle-estado');
        
        // Gestión de sesiones (historial completo)
        Route::get('/sesiones', [SesionController::class, 'index'])->name('sesiones.index');
        Route::post('/sesiones/{id}/finalizar', [SesionController::class, 'finalizarManual'])->name('sesiones.finalizar');
        Route::delete('/sesiones/{id}', [SesionController::class, 'destroy'])->name('sesiones.destroy');
        
        // Reportes
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/mensual', [ReporteController::class, 'mensual'])->name('reportes.mensual');
        Route::get('/reportes/anual', [ReporteController::class, 'anual'])->name('reportes.anual');
        Route::get('/reportes/resumen-rapido', [ReporteController::class, 'resumenRapido'])->name('reportes.resumen-rapido');
        Route::get('/reportes/exportar', [ReporteController::class, 'exportar'])->name('reportes.exportar');
        Route::get('/reportes/diario', function() {
            return redirect()->route('reportes.mensual');
        })->name('reportes.diario');
        
        // Soporte
        Route::get('/admin/soporte', [SoporteController::class, 'index'])->name('soporte.index');
        Route::get('/admin/soporte/{soporte}', [SoporteController::class, 'show'])->name('soporte.show');
        Route::patch('/admin/soporte/{soporte}/estado', [SoporteController::class, 'updateEstado'])->name('soporte.update-estado');
        Route::delete('/admin/soporte/{soporte}', [SoporteController::class, 'destroy'])->name('soporte.destroy');
    });
});

// Rutas de autenticación (incluidas por Breeze)
require __DIR__.'/auth.php';

// Rutas de fallback para usuarios no autorizados
Route::fallback(function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('sesiones.scanner');
        }
    }
    return redirect()->route('login');
});