<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRadicacionGlosaTable0501 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('radicacion_glosas', function (Blueprint $table) {
            $table->string('codigo')->nullable();
            $table->text('valor_aceptado')->nullable();
            $table->text('valor_no_aceptado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('radicacion_glosas', function (Blueprint $table) {
            $table->string('codigo')->nullable();
            $table->text('valor_aceptado')->nullable();
            $table->text('valor_no_aceptado')->nullable();
        });
    }
}
