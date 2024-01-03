<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrazabilidadPacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trazabilidad_pacientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('paciente_id')->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->bigInteger('entidad_id')->nullable()->unsigned()->index();
            $table->foreign('entidad_id')->references('id')->on('entidades');
            $table->integer('anio');
            $table->integer('mes');
            $table->integer('dia1');
            $table->integer('dia2');
            $table->integer('dia3');
            $table->integer('dia4');
            $table->integer('dia5');
            $table->integer('dia6');
            $table->integer('dia7');
            $table->integer('dia8');
            $table->integer('dia9');
            $table->integer('dia10');
            $table->integer('dia11');
            $table->integer('dia12');
            $table->integer('dia13');
            $table->integer('dia14');
            $table->integer('dia15');
            $table->integer('dia16');
            $table->integer('dia17');
            $table->integer('dia18');
            $table->integer('dia19');
            $table->integer('dia20');
            $table->integer('dia21');
            $table->integer('dia22');
            $table->integer('dia23');
            $table->integer('dia24');
            $table->integer('dia25');
            $table->integer('dia26');
            $table->integer('dia27');
            $table->integer('dia28');
            $table->integer('dia29');
            $table->integer('dia30');
            $table->integer('dia31');
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
        Schema::dropIfExists('trazabilidad_pacientes');
    }
}
