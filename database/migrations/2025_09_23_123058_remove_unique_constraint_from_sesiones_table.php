<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sesiones', function (Blueprint $table) {
            // Verificar si existe el índice antes de eliminarlo
            $indexes = collect(DB::select("SHOW INDEX FROM sesiones"))
                        ->pluck('Key_name')
                        ->toArray();
            
            // Solo eliminar si existe
            if (in_array('unique_active_session', $indexes)) {
                $table->dropUnique('unique_active_session');
            }
            
            // Verificar si ya existen los índices antes de crearlos
            if (!in_array('idx_alumno_estado', $indexes)) {
                $table->index(['alumno_id', 'estado'], 'idx_alumno_estado');
            }
            
            if (!in_array('idx_alumno_fecha', $indexes)) {
                $table->index(['alumno_id', 'fecha'], 'idx_alumno_fecha');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sesiones', function (Blueprint $table) {
            // Verificar qué índices existen antes de eliminar
            $indexes = collect(DB::select("SHOW INDEX FROM sesiones"))
                        ->pluck('Key_name')
                        ->toArray();
            
            if (in_array('idx_alumno_estado', $indexes)) {
                $table->dropIndex('idx_alumno_estado');
            }
            
            if (in_array('idx_alumno_fecha', $indexes)) {
                $table->dropIndex('idx_alumno_fecha');
            }
            
            // No restaurar la restricción problemática
            // $table->unique(['alumno_id', 'estado'], 'unique_active_session');
        });
    }
};