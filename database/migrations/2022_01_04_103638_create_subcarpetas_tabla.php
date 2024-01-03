<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcarpetasTabla extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subcarpetas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('ruta');

            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');


            $table->bigInteger('carpeta_id')->unsigned()->index()->nullable();
            $table->foreign('carpeta_id')->references('id')->on('documentales');

            $table->bigInteger('subcarpeta_id')->unsigned()->index()->nullable();
            $table->foreign('subcarpeta_id')->references('id')->on('subcarpetas');
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
        Schema::dropIfExists('subcarpetas');
    }
}
