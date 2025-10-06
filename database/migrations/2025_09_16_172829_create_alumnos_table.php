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
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            
            // Datos personales
            $table->string('nombre_completo');
            $table->string('rut_dni')->unique(); // Único para evitar duplicados
            $table->string('npi')->unique(); // Número de identificación para QR - ÚNICO
            $table->string('correo')->unique()->nullable(); // Email opcional pero único si existe
            
            // QR Code
            $table->text('qr_code')->nullable(); // Guardamos el contenido del QR
            $table->string('qr_image_path')->nullable(); // Ruta del archivo de imagen QR
            
            // Metadatos útiles
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Para datos adicionales futuros
            
            // Timestamps automáticos
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index('npi'); // Índice principal para scanner
            $table->index('rut_dni');
            $table->index('is_active');
            $table->index(['is_active', 'npi']); // Índice compuesto para búsquedas frecuentes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};