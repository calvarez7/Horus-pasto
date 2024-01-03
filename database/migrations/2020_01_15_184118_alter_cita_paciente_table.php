<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCitaPacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {   
            $table->string('tratamientoncologico')->nullable()->after('Datetimesalida');
            $table->string('cirujiaoncologica')->nullable()->after('tratamientoncologico');
            $table->string('ncirujias')->nullable()->after('cirujiaoncologica');
            $table->string('iniciocirujia')->nullable()->after('ncirujias');
            $table->string('fincirujia')->nullable()->after('iniciocirujia');
            $table->string('recibioradioterapia')->nullable()->after('fincirujia');
            $table->string('inicioradioterapia')->nullable()->after('recibioradioterapia');
            $table->string('finradioterapia')->nullable()->after('inicioradioterapia');
            $table->string('nsesiones')->nullable()->after('finradioterapia');
            $table->string('intencion')->nullable()->after('nsesiones');
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
            //
        });
    }
}
