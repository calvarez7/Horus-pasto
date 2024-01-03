<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('au_temps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('billNumber');
            $table->string('documentType');
            $table->string('documentNumber');
            $table->string('outputDiagnosis');
            $table->string('entryDate');
            $table->string('entryHour');
            $table->string('authNumber')->nullable();
            $table->string('externalCause');
            $table->string('diagnosisRelationshipOutput1')->nullable();
            $table->string('diagnosisRelationshipOutput2')->nullable();
            $table->string('diagnosisRelationshipOutput3')->nullable();
            $table->string('userDestination')->nullable();
            $table->string('outputStatus');
            $table->string('basicCauseDeath')->nullable();
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
        Schema::dropIfExists('au_temps');
    }
}
