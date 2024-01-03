<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartamentoReceptorToNovedadesPacientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('novedades_pacientes', function (Blueprint $table) {
            $table->string('departamentoReceptor')->nullable();
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
            $table->string('departamentoReceptor')->nullable();
        });
    }
}
