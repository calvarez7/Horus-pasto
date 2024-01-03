<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadesHelpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividades_help', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Categoria_id')->unsigned()->index();
            $table->foreign('Categoria_id')->references('id')->on('Categorias_help');
            $table->string('Nombre');
            $table->string('Descripcion');
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
        Schema::dropIfExists('actividades_help');
    }
}
