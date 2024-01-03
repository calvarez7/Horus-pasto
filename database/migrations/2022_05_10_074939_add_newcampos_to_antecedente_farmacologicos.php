<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewcamposToAntecedenteFarmacologicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('antecedente_farmacologicos', function (Blueprint $table) {
            $table->string('Alimneto')->nullable();
            $table->string('Ambientales')->nullable();
            $table->string('OtrasAlergias')->nullable();

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
            $table->string('Alimneto')->nullable();
            $table->string('Ambientales')->nullable();
            $table->string('OtrasAlergias')->nullable();
        });
    }
}
