<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNuevosToAntecedenteFarmacologicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('antecedente_farmacologicos', function (Blueprint $table) {
            $table->string('observacionalimneto')->nullable();
            $table->string('observacionambiental')->nullable();
            $table->string('observacionotras')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('antecedente_farmacologicos', function (Blueprint $table) {
            $table->string('observacionalimneto')->nullable();
            $table->string('observacionambiental')->nullable();
            $table->string('observacionotras')->nullable();
        });
    }
}
