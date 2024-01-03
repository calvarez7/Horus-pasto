<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriasHelpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias_help', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Area_id')->unsigned()->index();
            $table->foreign('Area_id')->references('id')->on('Areas_help');
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
        Schema::dropIfExists('categorias_help');
    }
}
