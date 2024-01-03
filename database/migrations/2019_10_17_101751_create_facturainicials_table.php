<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturainicialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturainicials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Tipo')->nullable();
            $table->string('Numero')->nullable();
            $table->date('Fecha')->nullable();
            $table->string('Cod_int')->nullable();
            $table->string('Descripcion')->nullable();
            $table->string('Presentacion')->nullable();
            $table->string('Nom_com')->nullable();
            $table->string('Cum')->nullable();
            $table->string('Lote')->nullable();
            $table->date('F_venc')->nullable();
            $table->string('Laboratorio')->nullable();
            $table->integer('Embalaje')->nullable();
            $table->integer('Cajas')->nullable();
            $table->integer('Unidades')->nullable();
            $table->decimal('Valor')->nullable();
            $table->integer('Total')->nullable();
            $table->string('Nit')->nullable();
            $table->string('Pedido')->nullable();
            $table->decimal('Unidades_caj_emb')->nullable();
            $table->decimal('Valor_unitario')->nullable();
            $table->integer('Bodega_id')->nullable();
            $table->integer('Detallearticulo_id')->nullable();
            $table->string('Usuario')->nullable();
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
        Schema::dropIfExists('facturainicials');
    }
}
