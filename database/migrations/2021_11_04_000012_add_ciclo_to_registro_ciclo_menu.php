<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCicloToRegistroCicloMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registro_ciclo_menu', function (Blueprint $table) {
            $table->bigInteger('ciclo_id')->nullable()->unsigned()->index();
            $table->foreign('ciclo_id')->references('id')->on('ciclos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registro_ciclo_menu', function (Blueprint $table) {
            $table->bigInteger('ciclo_id')->nullable()->unsigned()->index();
            $table->foreign('ciclo_id')->references('id')->on('ciclos');
        });
    }
}
