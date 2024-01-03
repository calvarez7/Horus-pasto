<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistroCicloMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_ciclo_menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha');
            $table->string('numero_personas');
            $table->bigInteger('ciclo_menu_id')->unsigned()->nullable()->index();
            $table->foreign('ciclo_menu_id')->references('id')->on('ciclos_menus');
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
        Schema::dropIfExists('registro_ciclo_menu');
    }
}
