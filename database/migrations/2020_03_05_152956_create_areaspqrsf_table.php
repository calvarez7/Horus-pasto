<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreaspqrsfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areaspqrsf', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Area_id')->nullable()->unsigned()->index();
            $table->foreign('Area_id')->references('id')->on('areas');
            $table->bigInteger('Pqrsf_id')->unsigned()->index();
            $table->foreign('Pqrsf_id')->references('id')->on('Pqrsfs');
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
        Schema::dropIfExists('areaspqrsf');
    }
}
