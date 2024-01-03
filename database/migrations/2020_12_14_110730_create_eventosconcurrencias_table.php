<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventosconcurrenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventosconcurrencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tipo_id')->nullable()->unsigned()->index();
            $table->foreign('tipo_id')->references('id')->on('tipos');
            $table->bigInteger('registroconcurrencias_id')->nullable()->unsigned()->index();
            $table->foreign('registroconcurrencias_id')->references('id')->on('registroconcurrencias');
            $table->string('eventos_seguridad')->nullable();
            $table->string('observacion_seguridad')->nullable();
            $table->string('costo_evitado')->nullable();
            $table->string('descripcion_costo_evitado')->nullable();
            $table->string('valor_costo_evitado')->nullable();
            $table->string('costo_evitable')->nullable();
            $table->string('descripción_costo_evitable')->nullable();
            $table->string('valor_costo_evitable')->nullable();
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
        Schema::dropIfExists('eventosconcurrencias');
    }
}
