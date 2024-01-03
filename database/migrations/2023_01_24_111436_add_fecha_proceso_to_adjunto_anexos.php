<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechaProcesoToAdjuntoAnexos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adjunto_anexos', function (Blueprint $table) {
            $table->date('fecha_proceso')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adjunto_anexos', function (Blueprint $table) {
            $table->date('fecha_proceso')->nullable();
        });
    }
}
