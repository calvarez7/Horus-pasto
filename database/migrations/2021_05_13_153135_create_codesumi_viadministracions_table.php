<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodesumiViadministracionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codesumi_viadministracions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('codesumi_id')->unsigned()->index();
            $table->foreign('codesumi_id')->references('id')->on('codesumis');
            $table->bigInteger('viadministracion_id')->unsigned()->index();
            $table->foreign('viadministracion_id')->references('id')->on('viadministracions');
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
        Schema::dropIfExists('codesumi_viadministracions');
    }
}
