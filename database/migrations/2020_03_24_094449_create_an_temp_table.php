<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('an_temps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('billNumber');
            $table->string('documentType');
            $table->string('documentNumber');
            $table->string('cie10');
            $table->string('birthDate');
            $table->string('gestationalAge');
            $table->string('birthHour');
            $table->string('prenatalManagement');
            $table->string('gender');
            $table->string('weight');
            $table->string('causeDeath')->nullable();
            $table->string('deathDate')->nullable();
            $table->string('deathHour')->nullable();
            $table->bigInteger('paq_temps_id')->unsigned()->index();
            $table->foreign('paq_temps_id')->references('id')->on('paq_temps');
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
        Schema::dropIfExists('an_temps');
    }
}
