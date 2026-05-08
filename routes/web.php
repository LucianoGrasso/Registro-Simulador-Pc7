<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SoporteController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\VueloController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Página de bienvenida (redirige al login si no está autenticado)
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Dashboard datos actualizados (AJAX) - DEBE IR ANTES que /dashboard
Route::get('/dashboard/datos', [DashboardController::class, 'datosActualizados'])
    ->middleware(['auth'])
    ->name('dashboard.datos');

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
    
    // Finalizar sesión (disponible para todos)
    Route::post('/sesiones/{id}/finalizar-directa', [SesionController::class, 'finalizarSesionDirecta'])
        ->name('sesiones.finalizar-directa');
    
    // Sesiones activas (todos pueden ver)
    Route::get('/sesiones/activas', [SesionController::class, 'activas'])->name('sesiones.activas');
    Route::get('/sesiones/activas-ajax', [SesionController::class, 'activasAjax'])->name('sesiones.activas-ajax');
    
    // Información del operador (reemplaza profile para operadores)
    Route::get('/mi-informacion', function () {
        return view('operador.info');
    })->name('operador.info');
    
    // Soporte - Para todos los usuarios
    Route::get('/soporte/crear', [SoporteController::class, 'create'])->name('soporte.create');
    Route::post('/soporte', [SoporteController::class, 'store'])->name('soporte.store');
    Route::get('/mis-tickets', [SoporteController::class, 'misTickets'])->name('soporte.mis-tickets');
    
    // Visualizador de Vuelos (Telemetría)
    Route::get('/historial-vuelos', [VueloController::class, 'index'])->name('vuelos.index');
    Route::get('/vuelos/ver/{archivo}', [VueloController::class, 'show'])->name('vuelos.show');

    // Pantalla de Instrumentos IDU Genesys
    Route::get('/idu', function () {
        // Forzamos a que esta ruta use el archivo de React
            return Inertia::render('IDU/Welcome');
    })->name('Idu.index');

    // Pantalla del Reproductor IDU (REPLAY)
    Route::get('/idu/reproductor/{archivo}', function ($archivo) {
        // Buscamos el archivo en la carpeta public/vuelos
        $rutaCompleta = public_path('vuelos/' . $archivo);

        // Si el archivo no existe, mostramos error 404
        if (!file_exists($rutaCompleta)) {
            abort(404, 'El archivo de vuelo no existe en el servidor.');
        }

        // Leemos el JSON y lo convertimos a un arreglo de PHP
        $contenido = file_get_contents($rutaCompleta);
        $datosVuelo = json_decode($contenido, true);

        // Le inyectamos los datos a la vista de React
        return Inertia::render('IDU/Reproductor', [
            'historialVuelo' => $datosVuelo
        ]);
    })->name('idu.reproductor');

    // ===== RUTAS SOLO PARA ADMINISTRADORES =====
    
    Route::middleware('admin')->group(function () {
        
        // Perfil de usuario
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        
        // CRUD completo de alumnos
        Route::resource('alumnos', AlumnoController::class);
        
        // Rutas adicionales para alumnos
        Route::post('/alumnos/{id}/regenerar-qr', [AlumnoController::class, 'regenerarQR'])->name('alumnos.regenerar-qr');
        Route::get('/alumnos/{id}/descargar-qr', [AlumnoController::class, 'descargarQR'])->name('alumnos.descargar-qr');
        Route::patch('/alumnos/{id}/toggle-estado', [AlumnoController::class, 'toggleEstado'])->name('alumnos.toggle-estado');
        
        // ===== GESTIÓN DE SESIONES (ADMIN) - NUEVAS RUTAS =====
        
        // Listado y gestión completa de sesiones
        Route::get('/sesiones', [SesionController::class, 'index'])->name('sesiones.index');
        
        // Editar sesión
        Route::get('/sesiones/{id}/editar', [SesionController::class, 'edit'])->name('sesiones.edit');
        Route::put('/sesiones/{id}', [SesionController::class, 'update'])->name('sesiones.update');
        
        // Eliminar sesión
        Route::delete('/sesiones/{id}', [SesionController::class, 'destroy'])->name('sesiones.destroy');
        
        // Finalizar manualmente
        Route::post('/sesiones/{id}/finalizar', [SesionController::class, 'finalizarManual'])->name('sesiones.finalizar');
        
        // Reporte diario de sesiones
        Route::get('/sesiones/reporte-diario', [SesionController::class, 'reporteDiario'])->name('sesiones.reporte.diario');
        
        // ===== FIN NUEVAS RUTAS DE SESIONES =====
        
        // Reportes
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/mensual', [ReporteController::class, 'mensual'])->name('reportes.mensual');
        Route::get('/reportes/anual', [ReporteController::class, 'anual'])->name('reportes.anual');
        Route::get('/reportes/resumen-rapido', [ReporteController::class, 'resumenRapido'])->name('reportes.resumen-rapido');
        Route::get('/reportes/exportar', [ReporteController::class, 'exportar'])->name('reportes.exportar');
        Route::get('/reportes/diario', function() {
            return redirect()->route('reportes.mensual');
        })->name('reportes.diario');
        
        // Soporte - Admin
        Route::get('/admin/soporte', [SoporteController::class, 'index'])->name('soporte.index');
        Route::get('/admin/soporte/{soporte}', [SoporteController::class, 'show'])->name('soporte.show');
        Route::patch('/admin/soporte/{soporte}/estado', [SoporteController::class, 'updateEstado'])->name('soporte.update-estado');
        Route::delete('/admin/soporte/{soporte}', [SoporteController::class, 'destroy'])->name('soporte.destroy');

        // CRUD completo de instructores
        Route::resource('instructores', InstructorController::class)->except(['create', 'destroy']);
        Route::patch('/instructores/{instructor}/toggle-estado', [InstructorController::class, 'toggleEstado'])->name('instructores.toggle-estado');
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