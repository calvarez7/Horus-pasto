<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCreateImagenologiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('imagenologias', function (Blueprint $table) {
            $table->string('Tipodiagnostico')->nullable();
            $table->bigInteger('Cie10_id')->unsigned()->index()->nullable();
            $table->foreign('Cie10_id')->references('id')->on('Cie10s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imagenologias', function (Blueprint $table) {
            $table->string('Notaclaratoria')->nullable();
            $table->bigInteger('Cie10_id')->unsigned()->index()->nullable();
            $table->foreign('Cie10_id')->references('id')->on('Cie10s');
        });
    }
}
