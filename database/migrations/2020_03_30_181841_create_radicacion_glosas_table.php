<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRadicacionGlosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radicacion_glosas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('glosa_id')->nullable()->unsigned()->index();
            $table->foreign('glosa_id')->references('id')->on('glosas');
            $table->bigInteger('prestador_id')->nullable()->unsigned()->index();
            $table->foreign('prestador_id')->references('id')->on('prestadores');
            $table->text('repuesta_prestador')->nullable();
            $table->text('repuesta_sumimedical')->nullable();
            $table->text('archivo')->nullable();
            $table->bigInteger('estado_id')->nullable()->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('Estados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('radicacion_glosas');
    }
}
