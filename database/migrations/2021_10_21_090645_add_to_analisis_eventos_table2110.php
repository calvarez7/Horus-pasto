<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToAnalisisEventosTable2110 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analisis_eventos', function (Blueprint $table) {
            $table->text('descripcion_consecuencias')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analisis_eventos', function (Blueprint $table) {
            $table->text('descripcion_consecuencias')->nullable();
        });
    }
}
