<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ap_temps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('billNumber');
            $table->string('documentType');
            $table->string('documentNumber');
            $table->string('processDate');
            $table->string('authNumber')->nullable();
            $table->string('processCode');
            $table->string('processScope');
            $table->string('processFinaly');
            $table->string('personalServes')->nullable();
            $table->string('principalDiagnosis')->nullable();
            $table->string('diagnosisRelationship')->nullable();
            $table->string('complication')->nullable();
            $table->string('qxAct')->nullable();
            $table->string('processValue');
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
        Schema::dropIfExists('ap_temps');
    }
}
