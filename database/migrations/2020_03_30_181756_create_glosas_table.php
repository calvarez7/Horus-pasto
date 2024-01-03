<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('glosas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('estado_id')->nullable()->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('Estados');
            $table->bigInteger('User_id')->unsigned()->index();
            $table->foreign('User_id')->references('id')->on('Users');
            $table->bigInteger('ac_id')->nullable()->unsigned()->index();
            $table->foreign('ac_id')->references('id')->on('acs');
            $table->bigInteger('us_id')->nullable()->unsigned()->index();
            $table->foreign('us_id')->references('id')->on('us');
            $table->bigInteger('ap_id')->nullable()->unsigned()->index();
            $table->foreign('ap_id')->references('id')->on('aps');
            $table->bigInteger('au_id')->nullable()->unsigned()->index();
            $table->foreign('au_id')->references('id')->on('aus');
            $table->bigInteger('ah_id')->nullable()->unsigned()->index();
            $table->foreign('ah_id')->references('id')->on('ahs');
            $table->bigInteger('an_id')->nullable()->unsigned()->index();
            $table->foreign('an_id')->references('id')->on('ans');
            $table->bigInteger('am_id')->nullable()->unsigned()->index();
            $table->foreign('am_id')->references('id')->on('ams');
            $table->text('descripcion')->nullable();
            $table->text('valor')->nullable();
            $table->text('archivo')->nullable();
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
        Schema::dropIfExists('glosas');
    }
}
