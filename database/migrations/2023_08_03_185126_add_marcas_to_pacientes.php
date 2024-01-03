<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarcasToPacientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->boolean('ppp')->nullable();
            $table->boolean('abrazarte')->nullable();
            $table->boolean('latir_sentido')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->boolean('ppp')->nullable();
            $table->boolean('abrazarte')->nullable();
            $table->boolean('latir_sentido')->nullable();
        });
    }
}
