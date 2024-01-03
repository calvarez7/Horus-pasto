<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRadicacionSumiGlosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radicacion_sumi_glosas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('glosa_id')->nullable()->unsigned()->index();
            $table->foreign('glosa_id')->references('id')->on('glosas');
            $table->bigInteger('user_id')->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('repuesta_sumimedical')->nullable();
            $table->text('valor_aceptado')->nullable();
            $table->text('valor_no_aceptado')->nullable();
            $table->bigInteger('estado_id')->nullable()->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
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
        Schema::dropIfExists('radicacion_sumi_glosas');
    }
}
