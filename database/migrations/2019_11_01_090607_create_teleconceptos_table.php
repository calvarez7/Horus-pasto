<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeleconceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teleconceptos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Paciente_id')->unsigned()->index();
            $table->foreign('Paciente_id')->references('id')->on('Pacientes');
            $table->bigInteger('UserCrea_id')->unsigned()->index();
            $table->foreign('UserCrea_id')->references('id')->on('Users');
            $table->bigInteger('UserResponde_id')->unsigned()->index()->nullable();
            $table->foreign('UserResponde_id')->references('id')->on('Users');
            $table->string('Tipo_Solicitud');
            $table->string('descripcion',500);
            $table->string('ResumenHc',2500);
            $table->string('Respuesta',1000)->nullable();
            $table->string('Adjunto')->nullable();
            $table->bigInteger('Estado_id')->default('1')->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
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
        Schema::dropIfExists('teleconceptos');
    }
}
