<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ats', function (Blueprint $table) {
            $table->bigInteger('estado_id')->unsigned()->nullable()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ats', function (Blueprint $table) {
            $table->bigInteger('estado_id')->unsigned()->nullable()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
        });
    }
}
