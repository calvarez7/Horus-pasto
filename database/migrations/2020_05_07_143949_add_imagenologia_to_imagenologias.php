<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImagenologiaToImagenologias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('imagenologias', function (Blueprint $table) {
            $table->string('Tecnica')->nullable();
            $table->string('medico_imagenologia')->nullable();
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
            $table->strind('Tecnica')->nullable();
            $table->strind('medico_imagenologia')->nullable();
        });
    }
}
