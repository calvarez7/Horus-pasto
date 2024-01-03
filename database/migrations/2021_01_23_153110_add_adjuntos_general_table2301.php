<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdjuntosGeneralTable2301 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adjuntos_generals', function (Blueprint $table) {
            $table->bigInteger('prestador_id')->nullable()->unsigned()->index();
            $table->foreign('prestador_id')->references('id')->on('prestadores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adjuntos_generals', function (Blueprint $table) {
            $table->bigInteger('prestador_id')->nullable()->unsigned()->index();
            $table->foreign('prestador_id')->references('id')->on('prestadores');
        });
    }
}
