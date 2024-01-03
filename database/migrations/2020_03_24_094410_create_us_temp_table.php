<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('us_temps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('documentType');            
            $table->string('documentNumber');            
            $table->string('adminCode')->default('RES004');            
            $table->string('userType')->default('5');
            $table->string('firstName');
            $table->string('secondName')->nullable();
            $table->string('firstSurname');
            $table->string('secondSurname')->nullable();
            $table->string('age');
            $table->string('unit');
            $table->string('gender');
            $table->string('codeDaneDepartment');
            $table->string('codeDaneTown');
            $table->string('area');
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
        Schema::dropIfExists('us_temps');
    }
}
