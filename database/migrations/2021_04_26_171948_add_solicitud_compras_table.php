<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSolicitudComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitud_compras', function (Blueprint $table) {
            $table->bigInteger('bodega_id')->nullable()->unsigned()->index();
            $table->foreign('bodega_id')->references('id')->on('bodegas');
            $table->bigInteger('estado_id')->nullable()->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitud_compras', function (Blueprint $table) {
            $table->bigInteger('bodega_id')->nullable()->unsigned()->index();
            $table->foreign('bodega_id')->references('id')->on('bodegas');
            $table->bigInteger('estado_id')->nullable()->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
        });
    }
}
