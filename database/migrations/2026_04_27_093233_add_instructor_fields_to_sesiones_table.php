<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sesiones', function (Blueprint $table) {
            // Al quitar el "after", se irán al final de la tabla sin causar errores
            $table->boolean('es_instruccion')->default(false);
            $table->string('instructor_npi')->nullable();
        });
    }

    public function down()
    {
        Schema::table('sesiones', function (Blueprint $table) {
            $table->dropColumn(['es_instruccion', 'instructor_npi']);
        });
    }
};
