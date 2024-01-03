<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGlosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('glosas', function (Blueprint $table) {
            $table->bigInteger('at_id')->nullable()->unsigned()->index();
            $table->foreign('at_id')->references('id')->on('ats');
            $table->string('codigo')->nullable();
            $table->text('concepto')->nullable();
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
            $table->bigInteger('at_id')->nullable()->unsigned()->index();
            $table->foreign('at_id')->references('id')->on('ats');
            $table->string('codigo')->nullable();
            $table->text('concepto')->nullable();
        });
    }
}
