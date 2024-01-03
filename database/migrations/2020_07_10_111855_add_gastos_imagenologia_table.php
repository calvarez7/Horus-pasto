<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGastosImagenologiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gastos_imagenologia', function (Blueprint $table) {
            $table->bigInteger('actualizo_userid')->unsigned()->nullable()->index();
            $table->foreign('actualizo_userid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gastos_imagenologia', function (Blueprint $table) {
            $table->bigInteger('actualizo_userid')->unsigned()->nullable()->index();
            $table->foreign('actualizo_userid')->references('id')->on('users');
        });
    }
}
