<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('categoriaproducto_id')->unsigned()->index();
            $table->foreign('categoriaproducto_id')->references('id')->on('categoriaproductos');
            $table->bigInteger('estado_id')->default(1)->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('Estados');
            $table->string('nombre')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('imagen')->nullable();
            $table->text('presentacion')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->text('codigo_barras')->nullable();
            $table->boolean('requiere_empaque')->nullable();
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
        Schema::dropIfExists('productos');
    }
}
