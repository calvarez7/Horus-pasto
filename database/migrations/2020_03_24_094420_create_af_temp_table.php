<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAfTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('af_temps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('documentType');
            $table->string('documentNumber');
            $table->string('billNumber');
            $table->string('billDate');
            $table->string('startDate');
            $table->string('finishDate');
            $table->string('entityCode')->default('RES004');
            $table->string('adminName');
            $table->string('agreementNumber')->nullable();
            $table->string('benefitsPlan')->nullable();
            $table->string('policyNumber')->nullable();
            $table->string('copayValue')->nullable();
            $table->string('commissionValue')->nullable();
            $table->string('discountValue')->nullable();
            $table->string('netoValue')->nullable();
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
        Schema::dropIfExists('af_temps');
    }
}
