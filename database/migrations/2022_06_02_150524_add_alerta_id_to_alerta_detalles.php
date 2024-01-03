<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlertaIdToAlertaDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alerta_detalles', function (Blueprint $table) {
            $table->bigInteger('alertas_id')->nullable()->index();
            $table->foreign('alertas_id')->references('id')->on('alertas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alerta_detalles', function (Blueprint $table) {
            $table->bigInteger('alertas_id')->nullable()->index();
            $table->foreign('alertas_id')->references('id')->on('alertas');
        });
    }
}
