<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToAnalisisEventosTable28042022 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analisis_eventos', function (Blueprint $table) {
            $table->string('requiere_reporte_ente')->nullable();
            $table->string('requiere_marca_especifica')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analisis_eventos', function (Blueprint $table) {
            $table->string('requiere_reporte_ente')->nullable();
            $table->string('requiere_marca_especifica')->nullable();
        });
    }
}
