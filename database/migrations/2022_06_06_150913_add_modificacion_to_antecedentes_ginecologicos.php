<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModificacionToAntecedentesGinecologicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('antecedentes_ginecologicos', function (Blueprint $table) {
            $table->date('fecha_ultimoparto')->nullable();
            $table->string('planea_embarazo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('antecedentes_ginecologicos', function (Blueprint $table) {
            $table->date('fecha_ultimoparto')->nullable();
            $table->string('planea_embarazo')->nullable();
        });
    }
}
