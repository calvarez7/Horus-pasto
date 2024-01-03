<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoconcurrenciaToReferencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referencias', function (Blueprint $table) {
            $table->bigInteger('estadoconcurrencia_id')->nullable()->unsigned()->index();
            $table->foreign('estadoconcurrencia_id')->references('id')->on('estados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referencias', function (Blueprint $table) {
            $table->bigInteger('estadoconcurrencia_id')->nullable()->unsigned()->index();
            $table->foreign('estadoconcurrencia_id')->references('id')->on('estados');
        });
    }
}
