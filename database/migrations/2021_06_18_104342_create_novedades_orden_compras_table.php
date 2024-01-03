<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovedadesOrdenComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('novedades_orden_compras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ordencompras_id')->nullable()->unsigned()->index();
            $table->foreign('ordencompras_id')->references('id')->on('ordencompras');
            $table->bigInteger('detallearticulo_id')->nullable()->unsigned()->index();
            $table->foreign('detallearticulo_id')->references('id')->on('detallearticulos');
            $table->string('tipo_novedad')->nullable();
            $table->integer('cantidad')->nullable();
            $table->integer('nuevo_precio')->nullable();
            $table->string('lote')->nullable();
            $table->string('numero_factura')->nullable();
            $table->date('fecha_vence')->nullable();;
            $table->text('observacion')->nullable();
            $table->boolean('devolver')->nullable();
            $table->bigInteger('nuevoDetallearticulo_id')->nullable()->unsigned()->index();
            $table->foreign('nuevoDetallearticulo_id')->references('id')->on('detallearticulos');
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
        Schema::dropIfExists('novedades_orden_compras');
    }
}
