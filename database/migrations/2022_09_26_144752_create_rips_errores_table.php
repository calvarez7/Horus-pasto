<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRipsErroresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rips_errores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('archivo');
            $table->string('mensaje');
            $table->json('lineas');
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
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
        Schema::dropIfExists('rips_errores');
    }
}
