<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLensometriaAgudezavisualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lensometria_agudezavisuals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lateralidad_od')->nullable();
            $table->string('esf_od')->nullable();
            $table->string('cyl_od')->nullable();
            $table->string('eje_od')->nullable();
            $table->string('add_od')->nullable();
            $table->string('lateralidad_oi')->nullable();
            $table->string('esf_oi')->nullable();
            $table->string('cyl_oi')->nullable();
            $table->string('eje_oi')->nullable();
            $table->string('add_oi')->nullable();
            $table->string('checkboxSC')->nullable();
            $table->string('checkboxCC')->nullable();
            $table->string('agudeza_od')->nullable();
            $table->string('agudezavp_od')->nullable();
            $table->string('agudeza_oi')->nullable();
            $table->string('agudezavp_oi')->nullable();
            $table->string('ocular_vl')->nullable();
            $table->string('ocular_vp')->nullable();
            $table->string('ocular_ppc')->nullable();
            $table->bigInteger('citapaciente_id')->unsigned()->index();
            $table->foreign('citapaciente_id')->references('id')->on('cita_paciente');
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
        Schema::dropIfExists('lensometria_agudezavisuals');
    }
}
