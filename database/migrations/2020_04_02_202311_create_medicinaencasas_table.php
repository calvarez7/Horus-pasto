<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicinaencasasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicinaencasas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Estado_id')->default(1)->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
            $table->bigInteger('citaPaciente_id')->unsigned()->index();
            $table->foreign('citaPaciente_id')->references('id')->on('cita_paciente');
            $table->string('remite')->nullable();
            $table->string('telIPSremite')->nullable();
            $table->string('emailmedico')->nullable();
            $table->string('nombrecuidador')->nullable();
            $table->string('celularcuidador')->nullable();
            $table->string('responsablepaciente')->nullable();
            $table->string('celularresponsable')->nullable();
            $table->string('indicebarthel')->nullable();
            $table->string('presenciaconfusion')->nullable();
            $table->integer('puntuacionagudo')->nullable();
            $table->string('estrategiacovi')->nullable();
            $table->string('voluntariamente')->nullable();
            $table->string('agua')->nullable();
            $table->string('luz')->nullable();
            $table->string('nevera')->nullable();
            $table->string('bano')->nullable();
            $table->string('internet')->nullable();
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
        Schema::dropIfExists('medicinaencasas');
    }
}
