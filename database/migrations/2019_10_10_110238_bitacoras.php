<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Bitacoras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('User_id')->nullable()->unsigned()->index();
            $table->foreign('User_id')->references('id')->on('users');
            $table->bigInteger('Referencia_id')->nullable()->unsigned()->index();
            $table->foreign('Referencia_id')->references('id')->on('referencias');
            $table->integer('Estado')->default(1);
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
        Schema::dropIfExists('bitacoras');
    }
}
