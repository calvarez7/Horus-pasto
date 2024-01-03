<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposToEstilovidas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estilovidas', function (Blueprint $table) {
            $table->string('Exposicion')->nullable();
            $table->string('Bano')->nullable();
            $table->string('Bucal')->nullable();
            $table->string('Posiciones')->nullable();
            $table->string('Esfinter')->nullable();
            $table->string('esfinterRectal')->nullable();
            $table->boolean('checkboxesfinterRectal')->nullable();
            $table->string('CaracteristicasOrina')->nullable();
            $table->string('leche')->nullable();
            $table->string('alimento')->nullable();
            $table->string('introduccionAlimentos')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estilovidas', function (Blueprint $table) {
            $table->string('Exposicion')->nullable();
            $table->string('Bano')->nullable();
            $table->string('Bucal')->nullable();
            $table->string('Posiciones')->nullable();
            $table->string('Esfinter')->nullable();
            $table->string('esfinterRectal')->nullable();
            $table->boolean('checkboxesfinterRectal')->nullable();
            $table->string('CaracteristicasOrina')->nullable();
            $table->string('leche')->nullable();
            $table->string('alimento')->nullable();
            $table->string('introduccionAlimentos')->nullable();
        });
    }
}
