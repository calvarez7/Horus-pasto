<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValorCitaToCitaPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {
            $table->bigInteger('valor_cita')->nullable();
            $table->bigInteger('cobro_estado_id')->unsigned()->nullable()->index();
            $table->foreign('cobro_estado_id')->references('id')->on('estados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {
            $table->bigInteger('valor_cita')->nullable();
            $table->bigInteger('cobro_estado_id')->unsigned()->nullable()->index();
            $table->foreign('cobro_estado_id')->references('id')->on('estados');
        });
    }
}
