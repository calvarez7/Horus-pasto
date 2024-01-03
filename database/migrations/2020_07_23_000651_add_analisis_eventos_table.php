<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnalisisEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analisis_eventos', function (Blueprint $table) {
            $table->bigInteger('usercreo_id')->unsigned()->nullable()->index();
            $table->foreign('usercreo_id')->references('id')->on('users');
            $table->bigInteger('usercerro_id')->unsigned()->nullable()->index();
            $table->foreign('usercerro_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analisis_eventos', function (Blueprint $table) {
            $table->bigInteger('usercreo_id')->unsigned()->nullable()->index();
            $table->foreign('usercreo_id')->references('id')->on('users');
            $table->bigInteger('usercerro_id')->unsigned()->nullable()->index();
            $table->foreign('usercerro_id')->references('id')->on('users');
        });
    }
}
