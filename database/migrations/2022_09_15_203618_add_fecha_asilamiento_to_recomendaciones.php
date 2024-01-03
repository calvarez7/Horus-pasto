<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechaAsilamientoToRecomendaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recomendaciones_clinicas', function (Blueprint $table) {
            $table->date('fecha_aislamiento')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recomendaciones_clinicas', function (Blueprint $table) {
            $table->date('fecha_aislamiento')->nullable();

        });
    }
}
