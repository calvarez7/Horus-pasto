<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMunicipioReceptorToNovedadesPacientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('novedades_pacientes', function (Blueprint $table) {
            $table->string('municipio_receptor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('novedades_pacientes', function (Blueprint $table) {
            $table->string('municipio_receptor')->nullable();
        });
    }
}
