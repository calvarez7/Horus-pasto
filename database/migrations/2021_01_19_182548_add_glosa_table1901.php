<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGlosaTable1901 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('glosas', function (Blueprint $table) {
            $table->bigInteger('af_id')->nullable()->unsigned()->index();
            $table->foreign('af_id')->references('id')->on('afs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('glosas', function (Blueprint $table) {
            $table->bigInteger('af_id')->nullable()->unsigned()->index();
            $table->foreign('af_id')->references('id')->on('afs');
        });
    }
}
