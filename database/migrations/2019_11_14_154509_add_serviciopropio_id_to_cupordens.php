<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiciopropioIdToCupordens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cupordens', function (Blueprint $table) {
            $table->bigInteger('Cup_id')->nullable()->change();
            $table->bigInteger('Serviciopropio_id')->nullable();
            $table->foreign('Serviciopropio_id')->references('id')->on('Codepropios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cupordens', function (Blueprint $table) {
            $table->bigInteger('Cup_id')->change();
        });
    }
}
