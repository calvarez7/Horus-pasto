<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCamposToRegistroInventarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registro_inventarios', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('proveedor')->nullable();
            $table->string('nit')->nullable();
            $table->text('descripcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registro_inventarios', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('proveedor')->nullable();
            $table->string('nit')->nullable();
        });
    }
}
