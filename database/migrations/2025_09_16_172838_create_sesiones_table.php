<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sesiones', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->string('npi'); // Para búsqueda directa sin JOIN
            
            // Fechas y tiempos
            $table->date('fecha'); // Fecha de la sesión
            $table->datetime('hora_inicio');
            $table->datetime('hora_fin')->nullable();
            $table->integer('duracion_minutos')->nullable(); // Se calcula automáticamente
            
            // Datos de la sesión
            $table->text('actividad'); // Descripción de lo que hizo
            $table->enum('estado', ['activa', 'finalizada', 'cancelada'])->default('activa');
            
            // Control de usuarios
            $table->foreignId('usuario_inicio_id')->constrained('users');
            $table->foreignId('usuario_fin_id')->nullable()->constrained('users');
            
            // Metadatos adicionales
            $table->json('detalles')->nullable(); // Para datos extra (ej: tipo de avión simulado)
            $table->text('observaciones')->nullable(); // Comentarios adicionales
            
            $table->timestamps();
            
            // Índices optimizados para consultas frecuentes
            $table->index('npi'); // Para scanner rápido
            $table->index('fecha'); // Para reportes por fecha
            $table->index('estado'); // Para sesiones activas
            $table->index(['fecha', 'estado']); // Para reportes diarios
            $table->index(['alumno_id', 'fecha']); // Para historial por alumno
            $table->index('hora_inicio'); // Para ordenamiento cronológico
            
            // Restricción: solo una sesión activa por alumno
            $table->unique(['alumno_id', 'estado'], 'unique_active_session')
                  ->where('estado', 'activa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesiones');
    }
};