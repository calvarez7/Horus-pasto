<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrecioProveedorMedicamentosTable1305 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('precio_proveedor_medicamentos', function (Blueprint $table) {
            $table->float('iva')->nullable();
            $table->float('iva_facturacion')->nullable();
            $table->float('precio_venta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('precio_proveedor_medicamentos', function (Blueprint $table) {
            $table->float('iva')->nullable();
            $table->float('iva_facturacion')->nullable();
            $table->float('precio_venta')->nullable();
        });
    }
}
