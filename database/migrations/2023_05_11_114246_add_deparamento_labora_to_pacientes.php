<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeparamentoLaboraToPacientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->bigInteger('Dpto_Residencia_id')->nullable()->unsigned()->index();
            $table->foreign('Dpto_Residencia_id')->references('id')->on('departamentos');
            $table->bigInteger('Dpto_Labora_id')->nullable()->unsigned()->index();
            $table->foreign('Dpto_Labora_id')->references('id')->on('departamentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->bigInteger('Dpto_Residencia_id')->nullable()->unsigned()->index();
            $table->foreign('Dpto_Residencia_id')->references('id')->on('departamentos');
            $table->bigInteger('Dpto_Labora_id')->nullable()->unsigned()->index();
            $table->foreign('Dpto_Labora_id')->references('id')->on('departamentos');
        });
    }
}

