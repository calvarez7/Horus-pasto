<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailCmedicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_cmedicas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('prestador_id')->unsigned()->index();
            $table->foreign('prestador_id')->references('id')->on('prestadores');
            $table->string('email');
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
        Schema::dropIfExists('email_cmedicas');
    }
}
