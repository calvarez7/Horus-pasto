<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('am_temps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('billNumber');
            $table->string('documentType');
            $table->string('documentNumber');
            $table->string('authNumber')->nullable();
            $table->string('cum')->nullable();
            $table->string('medicamentType')->nullable();
            $table->string('numberUnits')->nullable();
            $table->string('unitValue')->nullable();
            $table->string('totalValue')->nullable();
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
        Schema::dropIfExists('am_temps');
    }
}
