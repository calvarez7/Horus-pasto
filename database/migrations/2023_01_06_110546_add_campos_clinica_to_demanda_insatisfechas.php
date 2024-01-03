<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposClinicaToDemandaInsatisfechas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demanda_insatisfechas', function (Blueprint $table) {
            $table->string('fecha_cita_victoriana')->nullable();
            $table->string('consultorio_victoriana')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demanda_insatisfechas', function (Blueprint $table) {
            $table->string('fecha_cita_victoriana')->nullable();
            $table->string('consultorio_victoriana')->nullable();
        });
    }
}
