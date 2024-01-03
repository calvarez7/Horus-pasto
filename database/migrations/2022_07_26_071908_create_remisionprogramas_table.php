<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemisionprogramasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remisionprogramas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('primerainfancia')->nullable();
            $table->boolean('infancia')->nullable();
            $table->boolean('adolescencia')->nullable();
            $table->boolean('juventud')->nullable();
            $table->boolean('adultez')->nullable();
            $table->boolean('vejez')->nullable();
            $table->boolean('maternoperinatal')->nullable();
            $table->boolean('preconcepcional')->nullable();
            $table->boolean('psicoprofilactico')->nullable();
            $table->boolean('educativo')->nullable();
            $table->boolean('lactanciamaterna')->nullable();
            $table->boolean('cancerdecervix')->nullable();
            $table->boolean('cancerdemama')->nullable();
            $table->boolean('cancercolorectal')->nullable();
            $table->boolean('cancerdeprostata')->nullable();
            $table->boolean('vacunacion')->nullable();
            $table->boolean('controlpospartoyreciennacido')->nullable();
            $table->boolean('planificacionfamiliar')->nullable();
            $table->boolean('riesgocardiovascular')->nullable();
            $table->boolean('otrogrupoderiesgo')->nullable();
            $table->string('cual')->nullable();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
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
        Schema::dropIfExists('remisionprogramas');
    }
}
