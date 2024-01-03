<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCie10InvalidosCupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cie10_invalidos_cups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cie10_id')->unsigned()->index();
            $table->foreign('cie10_id')->references('id')->on('cie10s');
            $table->bigInteger('cup_id')->unsigned()->index();
            $table->foreign('cup_id')->references('id')->on('Cups');
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
        Schema::dropIfExists('cie10_invalidos_cups');
    }
}
