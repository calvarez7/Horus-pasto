<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMedimasToPacientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->integer('vlr_upc')->nullable();
            $table->date('fecha_ini_cont')->nullable();
            $table->date('fecha_fin_cont')->nullable();
            $table->integer('valor_cont_cap')->nullable();
            $table->integer('valot_cont_pyp')->nullable();
            $table->integer('sem_cot')->nullable();
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
            $table->integer('vlr_upc')->nullable();
            $table->date('fecha_ini_cont')->nullable();
            $table->date('fecha_fin_cont')->nullable();
            $table->integer('valor_cont_cap')->nullable();
            $table->integer('valot_cont_pyp')->nullable();
            $table->integer('sem_cot')->nullable();
        });
    }
}
