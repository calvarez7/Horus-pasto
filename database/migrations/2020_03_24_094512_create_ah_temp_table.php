<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAhTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ah_temps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('billNumber');
            $table->string('documentType');
            $table->string('documentNumber');
            $table->string('entryDay');
            $table->string('entryDate');
            $table->string('entryHour');
            $table->string('authNumber');
            $table->string('causeExternal');
            $table->string('entryDiagnosis');
            $table->string('outputDiagnosis');
            $table->string('diagnosisRelationship1');
            $table->string('diagnosisRelationship2');
            $table->string('diagnosisRelationship3');
            $table->string('diagnosisComplication');
            $table->string('outputStatus');
            $table->string('diagnosisDeathCause')->nullable();
            $table->string('outputDate');
            $table->string('outputHour');
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
        Schema::dropIfExists('ah_temps');
    }
}
