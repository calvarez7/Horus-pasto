<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValornivelesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valorniveles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('entidad_id')->unsigned()->index();
            $table->foreign('entidad_id')->references('id')->on('entidades');
            $table->bigInteger('cup_id')->unsigned()->index();
            $table->foreign('cup_id')->references('id')->on('cups');
            $table->integer('nivel')->nullable();
            $table->integer('valor')->nullable();
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
        Schema::dropIfExists('valorniveles');
    }
}
