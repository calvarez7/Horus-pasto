<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCreatePacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('Nombreacompañante')->nullable()->after('Nivelestudio');
           $table->string('Telefonoacompañante')->nullable()->after('Nivelestudio');
           $table->string('Nombreresponsable')->nullable()->after('Nivelestudio');
           $table->string('Telefonoresponsable')->nullable()->after('Nivelestudio');
           $table->string('Aseguradora')->nullable()->after('Nivelestudio');
           $table->string('Tipovinculacion')->nullable()->after('Nivelestudio');
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
           $table->string('Nombreacompañante')->nullable()->after('Nivelestudio');
           $table->string('Telefonoacompañante')->nullable()->after('Nivelestudio');
           $table->string('Nombreresponsable')->nullable()->after('Nivelestudio');
           $table->string('Telefonoresponsable')->nullable()->after('Nivelestudio');
           $table->string('Aseguradora')->nullable()->after('Nivelestudio');
           $table->string('Tipovinculacion')->nullable()->after('Nivelestudio');
        });
    }
}
