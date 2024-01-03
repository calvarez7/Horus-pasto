<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCie10TeleconceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cie10_teleconceptos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cie10_id')->unsigned()->index();
            $table->foreign('cie10_id')->references('id')->on('cie10s');
            $table->bigInteger('teleconcepto_id')->unsigned()->index();
            $table->foreign('teleconcepto_id')->references('id')->on('teleconceptos');
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
        Schema::dropIfExists('cie10_teleconceptos');
    }
}
