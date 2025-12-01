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
            $table->string('archivo_vuelo')->nullable()->after('observaciones');
        });
    }

    public function down()
    {
        Schema::table('sesiones', function (Blueprint $table) {
            $table->dropColumn('archivo_vuelo');
        });
    }
};
