<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoToSolicitudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitudbodegas', function (Blueprint $table) {
            //
            $table->string('Motivo')->nullable();
            $table->bigInteger('Tipotransacion_id')->nullable()->unsigned()->index();
            $table->foreign('Tipotransacion_id')->references('id')->on('Tipotransacions');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitudbodegas', function (Blueprint $table) {
            //
        });
    }
}
