<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCupspqrsfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupspqrsf', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Cup_id')->unsigned()->index();
            $table->foreign('Cup_id')->references('id')->on('Cups');            
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
        Schema::dropIfExists('cupspqrsf');
    }
}
