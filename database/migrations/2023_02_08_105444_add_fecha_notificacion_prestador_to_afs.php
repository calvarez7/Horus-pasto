<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechaNotificacionPrestadorToAfs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('afs', function (Blueprint $table) {
            $table->dateTime('fecha_notificacion_prestador')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('afs', function (Blueprint $table) {
            $table->dateTime('fecha_notificacion_prestador')->nullable();
        });
    }
}
