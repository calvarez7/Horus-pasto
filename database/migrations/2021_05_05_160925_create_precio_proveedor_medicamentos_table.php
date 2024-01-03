<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrecioProveedorMedicamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precio_proveedor_medicamentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('precio_unidad');
            $table->bigInteger('sedeproveedore_id')->unsigned()->index();
            $table->foreign('sedeproveedore_id')->references('id')->on('sedeproveedores');
            $table->bigInteger('detallearticulo_id')->unsigned()->index();
            $table->foreign('detallearticulo_id')->references('id')->on('detallearticulos');
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
        Schema::dropIfExists('precio_proveedor_medicamentos');
    }
}
