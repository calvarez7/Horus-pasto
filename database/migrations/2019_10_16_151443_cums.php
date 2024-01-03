<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Expediente');
            $table->string('Producto');
            $table->string('Titular');
            $table->string('Registro_Sanitario');
            $table->string('Fecha_Expedicion');
            $table->string('Fecha_Vencimiento');
            $table->string('Estado_Registro');
            $table->string('Expediente_Cum');
            $table->string('Consecutivo');
            $table->string('Cantidad_Cum');
            $table->string('Descripción_Comercial');
            $table->string('Estado_Cum');
            $table->string('Fecha_Activo');
            $table->string('Fecha_Inactivo');
            $table->string('Muestra_Medica');
            $table->string('Unidad');
            $table->string('Atc');
            $table->string('Descripcion_Atc');
            $table->string('Via_Administracion');
            $table->string('Concentracion');
            $table->string('Principio_Activo');
            $table->string('Unidad_Medida');
            $table->string('Cantidad');
            $table->string('Unidad_Referencia');
            $table->string('Forma_Farmaceutica');
            $table->string('Cum_Validacion');
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
        Schema::dropIfExists('cums');
    }
}
