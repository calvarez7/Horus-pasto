<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricoPrecioProveedorMedicamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_precio_proveedor_medicamentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('precio_unidad');
            $table->bigInteger('sedeproveedore_id')->unsigned()->index();
            $table->foreign('sedeproveedore_id')->references('id')->on('sedeproveedores');
            $table->bigInteger('detallearticulo_id')->unsigned()->index();
            $table->foreign('detallearticulo_id')->references('id')->on('detallearticulos');
            $table->bigInteger('ordencompra_id')->unsigned()->index();
            $table->foreign('ordencompra_id')->references('id')->on('ordencompras');
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
        Schema::dropIfExists('historico_precio_proveedor_medicamentos');
    }
}
