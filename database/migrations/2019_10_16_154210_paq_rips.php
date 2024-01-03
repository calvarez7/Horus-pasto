<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaqRips extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paq_rips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Nombre');
            $table->bigInteger('User_id')->unsigned()->index();
            $table->foreign('User_id')->references('id')->on('Users')->OnDelete('cascade');
            $table->bigInteger('Reps_id')->unsigned()->index();
            $table->foreign('Reps_id')->references('id')->on('sedeproveedores')->OnDelete('cascade');
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
        Schema::dropIfExists('paq_rips');
    }
}
