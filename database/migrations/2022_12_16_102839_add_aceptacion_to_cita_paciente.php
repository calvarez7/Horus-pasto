<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAceptacionToCitaPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {
            $table->string('aceptacion_consentimiento')->nullable();
            $table->string('firmante')->nullable();
            $table->string('numero_documento_representante')->nullable();
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
            $table->string('aceptacion_consentimiento')->nullable();
            $table->string('firmante')->nullable();
            $table->string('numero_documento_representante')->nullable();

        });
    }
}
