<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToAdjuntoEventosTable13052022 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adjunto_eventos', function (Blueprint $table) {
            $table->bigInteger('analisisevento_id')->unsigned()->nullable()->index();
            $table->foreign('analisisevento_id')->references('id')->on('analisis_eventos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adjunto_eventos', function (Blueprint $table) {
            $table->bigInteger('analisisevento_id')->unsigned()->nullable()->index();
            $table->foreign('analisisevento_id')->references('id')->on('analisis_eventos');
        });
    }
}
