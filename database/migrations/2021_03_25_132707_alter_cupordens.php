<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCupordens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cupordens', function (Blueprint $table) {
            $table->string('cirujano')->nullable();
            $table->string('especialidad')->nullable();
            $table->date('fecha_Preanestesia')->nullable();
            $table->date('fecha_cirugia')->nullable();
            $table->date('fecha_ejecucion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cupordens', function (Blueprint $table) {
            $table->string('cirujano')->nullable();
            $table->string('especialidad')->nullable();
            $table->date('fecha_Preanestesia')->nullable();
            $table->date('fecha_cirugia')->nullable();
            $table->date('fecha_ejecucion')->nullable();
        });
    }
}
