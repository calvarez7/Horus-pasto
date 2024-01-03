<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuptutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuptutelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Cup_id')->unsigned()->index();
            $table->foreign('Cup_id')->references('id')->on('Cups');
            $table->bigInteger('Tutela_id')->unsigned()->index();
            $table->foreign('Tutela_id')->references('id')->on('Tutelas');
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
        Schema::dropIfExists('cuptutelas');
    }
}
