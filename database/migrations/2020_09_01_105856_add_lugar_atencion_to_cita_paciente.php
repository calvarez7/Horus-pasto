<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLugarAtencionToCitaPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {
            $table->bigInteger('lugar_atencion')->unsigned()->nullable()->index();
            $table->foreign('lugar_atencion')->references('id')->on('sedes_sumimedical');
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
            $table->bigInteger('lugar_atencion')->unsigned()->nullable()->index();
            $table->foreign('lugar_atencion')->references('id')->on('sedes_sumimedical');
        });
    }
}
