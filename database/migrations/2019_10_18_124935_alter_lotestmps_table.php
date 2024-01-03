<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLotestmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lotestmps', function (Blueprint $table) {
            $table->bigInteger('Usuario_id')->unsigned()->index()->nullable();
            $table->foreign('Usuario_id')->references('id')->on('Users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lotestmps', function (Blueprint $table) {
            $table->bigInteger('Usuario_id')->unsigned()->index()->nullable();
            $table->foreign('Usuario_id')->references('id')->on('Users');
        });
    }
}
