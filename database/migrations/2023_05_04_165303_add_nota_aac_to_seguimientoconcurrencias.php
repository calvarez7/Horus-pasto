<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotaAacToSeguimientoconcurrencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seguimientoconcurrencias', function (Blueprint $table) {
            $table->text('nota_aac')->nullable();
            $table->bigInteger('user_notaaac_id')->nullable()->unsigned()->index();
            $table->foreign('user_notaaac_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seguimientoconcurrencias', function (Blueprint $table) {
            $table->text('nota_aac')->nullable();
            $table->bigInteger('user_notaaac_id')->nullable()->unsigned()->index();
            $table->foreign('user_notaaac_id')->references('id')->on('users');
        });
    }
}
