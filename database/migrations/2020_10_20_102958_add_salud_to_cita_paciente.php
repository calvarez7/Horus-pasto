<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSaludToCitaPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {
            $table->bigInteger('user_medico_atiende')->nullable()->unsigned()->index();
            $table->foreign('user_medico_atiende')->references('id')->on('users');
            $table->string('salud_ocupacional')->nullable();
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
            $table->bigInteger('user_medico_atiende')->nullable()->unsigned()->index();
            $table->foreign('user_medico_atiende')->references('id')->on('users');
            $table->string('salud_ocupacional')->nullable();
        });
    }
}
