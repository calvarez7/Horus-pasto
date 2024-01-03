<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeguimientoToSeguimientoconcurrencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seguimientoconcurrencias', function (Blueprint $table) {
            $table->bigInteger('concurrenciaRegistro_id')->nullable()->unsigned()->index();
            $table->foreign('concurrenciaRegistro_id')->references('id')->on('registroconcurrencias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seguimientoconcurrencias', function (Blueprint $table) {
            $table->bigInteger('concurrenciaRegistro_id')->nullable()->unsigned()->index();
            $table->foreign('concurrenciaRegistro_id')->references('id')->on('registroconcurrencias');
        });
    }
}
