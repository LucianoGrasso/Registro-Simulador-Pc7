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
        Schema::table('instructores', function (Blueprint $table) {
            // Agregamos un PIN de 4 dígitos (por defecto 1234 para los que ya creaste)
            $table->string('pin', 4)->default('1234')->after('npi');
        });
    }

    public function down()
    {
        Schema::table('instructores', function (Blueprint $table) {
            $table->dropColumn('pin');
        });
    }
};
