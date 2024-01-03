<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParaclinicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paraclinicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('resultadoCreatinina')->nullable();
            $table->string('ultimaCreatinina')->nullable();
            $table->string('resultaGlicosidada')->nullable();
            $table->string('fechaGlicosidada')->nullable();
            $table->string('resultadoAlbuminuria')->nullable();
            $table->string('fechaAlbuminuria')->nullable();
            $table->string('resultadoColesterol')->nullable();
            $table->string('fechaColesterol')->nullable();
            $table->string('resultadoHdl')->nullable();
            $table->string('fechaHdl')->nullable();
            $table->string('resultadoLdl')->nullable();
            $table->string('fechaLdl')->nullable();
            $table->string('resultadoTrigliceridos')->nullable();
            $table->string('fechaTrigliceridos')->nullable();
            $table->string('resultadoGlicemia')->nullable();
            $table->string('fechaGlicemia')->nullable();
            $table->string('resultadoPht')->nullable();
            $table->string('fechaPht')->nullable();
            $table->string('resultadoHemoglobina')->nullable();
            $table->string('albumina')->nullable();
            $table->string('fechaAlbumina')->nullable();
            $table->string('fosforo')->nullable();
            $table->string('fechaFosforo')->nullable();
            $table->string('resultadoEkg')->nullable();
            $table->string('fechaEkg')->nullable();
            $table->string('glomerular')->nullable();
            $table->string('fechaGlomerular')->nullable();
            $table->bigInteger('usuario_id')->nullable()->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('Users');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->bigInteger('Citapaciente_id')->nullable()->unsigned()->index();
            $table->foreign('Citapaciente_id')->references('id')->on('cita_paciente');
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
        Schema::dropIfExists('paraclinicos');
    }
}
