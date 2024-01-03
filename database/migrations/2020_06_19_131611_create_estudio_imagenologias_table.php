<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstudioImagenologiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudio_imagenologias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cita_paciente_id')->unsigned()->index();
            $table->foreign('cita_paciente_id')->references('id')->on('cita_paciente');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->float('cantidad')->nullable();
            $table->string('via')->nullable();
            $table->float('peso')->nullable();
            $table->float('tiempo')->nullable();
            $table->float('exposicion')->nullable();
            $table->float('mas')->nullable();
            $table->float('kv')->nullable();
            $table->float('distancia')->nullable();
            $table->float('foco')->nullable();
            $table->float('total_dosis')->nullable();
            $table->text('observacion')->nullable(); 
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
        Schema::dropIfExists('estudio_imagenologias');
    }
}
