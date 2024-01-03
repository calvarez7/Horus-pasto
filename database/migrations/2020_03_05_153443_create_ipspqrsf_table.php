<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpspqrsfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ipspqrsf', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('IPS_id')->unsigned()->index();
            $table->foreign('IPS_id')->references('id')->on('Sedeproveedores');
            $table->bigInteger('Pqrsf_id')->unsigned()->index();
            $table->foreign('Pqrsf_id')->references('id')->on('Pqrsfs');
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
        Schema::dropIfExists('ipspqrsf');
    }
}
