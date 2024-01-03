<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenciaIDToCitaPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {
            $table->bigInteger('referencia_id')->nullable()->unsigned()->index();
            $table->foreign('referencia_id')->references('id')->on('referencias');
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
            $table->bigInteger('referencia_id')->nullable()->unsigned()->index();
            $table->foreign('referencia_id')->references('id')->on('referencias');
        });
    }
}
