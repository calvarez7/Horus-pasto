<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCupTipoAgendaToEspecialidadeTipoagenda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('especialidade_tipoagenda', function (Blueprint $table) {
            $table->bigInteger('Primeravez_id')->nullable()->unsigned()->index();
            $table->foreign('Primeravez_id')->references('id')->on('Cups');
            $table->bigInteger('Control_id')->nullable()->unsigned()->index();
            $table->foreign('Control_id')->references('id')->on('Cups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('especialidade_tipoagenda', function (Blueprint $table) {
            $table->bigInteger('Primeravez_id')->nullable()->unsigned()->index();
            $table->foreign('Primeravez_id')->references('id')->on('Cups');
            $table->bigInteger('Control_id')->nullable()->unsigned()->index();
            $table->foreign('Control_id')->references('id')->on('Cups');
        });
    }
}
