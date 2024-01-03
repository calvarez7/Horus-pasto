<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGestionSolicitudsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gestion_solicituds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('solicituides_gestionhumana_id')->unsigned()->index();
            $table->foreign('solicituides_gestionhumana_id')->references('id')->on('solicitudes__gestion_humanas'); 
            $table->bigInteger('estado_id')->default('1')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');  
            $table->bigInteger('respuesta_solicitud_id')->unsigned()->index();
            $table->foreign('respuesta_solicitud_id')->references('id')->on('respuesta_solicituds'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gestion_solicituds');
    }
}
