<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePaqueteServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_paquete_servicios', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad')->nullable();
            $table->integer('frecuencia')->nullable();
            $table->bigInteger('cup_id')->nullable()->unsigned()->index();
            $table->foreign('cup_id')->references('id')->on('cups');
            $table->bigInteger('paquete_servicio_id')->nullable()->unsigned()->index();
            $table->foreign('paquete_servicio_id')->references('id')->on('paquete_servicios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_paquete_servicios');
    }
}
