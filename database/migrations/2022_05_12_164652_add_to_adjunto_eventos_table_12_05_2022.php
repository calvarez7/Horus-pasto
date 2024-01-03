<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToAdjuntoEventosTable12052022 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adjunto_eventos', function (Blueprint $table) {
            $table->bigInteger('accionmejora_id')->unsigned()->nullable()->index();
            $table->foreign('accionmejora_id')->references('id')->on('acciones_mejoras');
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
            $table->bigInteger('accionmejora_id')->unsigned()->nullable()->index();
            $table->foreign('accionmejora_id')->references('id')->on('acciones_mejoras');
        });
    }
}
