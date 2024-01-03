<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesGestionHumanasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes__gestion_humanas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tipo_solicitud_gestion_humana_id')->unsigned()->index();
            $table->foreign('tipo_solicitud_gestion_humana_id')->references('id')->on('tipo_solicitud_gestion_humanas');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');  
            $table->text('descripcion');
            $table->bigInteger('area_id')->unsigned()->index();
            $table->foreign('area_id')->references('id')->on('areas_gestion_humanas'); 
            $table->bigInteger('estado_id')->default('1')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');        
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
        Schema::dropIfExists('solicitudes__gestion_humanas');
    }
}
