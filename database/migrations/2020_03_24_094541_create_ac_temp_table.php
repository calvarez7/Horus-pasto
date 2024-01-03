<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_temps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('billNumber');
            $table->string('documentType');
            $table->string('documentNumber');
            $table->string('consultationDate');
            $table->string('authNumber')->nullable();
            $table->string('consultationCode');
            $table->string('Finalidad_Consulta');
            $table->string('causeExternal');
            $table->string('principalDiagnosis');
            $table->string('codeRelationship1')->nullable();
            $table->string('codeRelationship2')->nullable();
            $table->string('codeRelationship3')->nullable();
            $table->string('principalDiagnosisType');
            $table->string('consultationValue');
            $table->string('feeValue');
            $table->string('netoValue');
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
        Schema::dropIfExists('ac_temps');
    }
}
