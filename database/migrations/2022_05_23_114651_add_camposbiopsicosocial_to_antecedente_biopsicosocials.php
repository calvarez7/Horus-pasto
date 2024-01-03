<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposbiopsicosocialToAntecedenteBiopsicosocials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('antecedente_biopsicosocials', function (Blueprint $table) {
            $table->string('rendimiento')->nullable();
            $table->string('aprendizaje')->nullable();
            $table->string('actitudAula')->nullable();
            $table->string('relacionamiento')->nullable();
            $table->string('testAlteraciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('antecedente_biopsicosocials', function (Blueprint $table) {
            $table->string('rendimiento')->nullable();
            $table->string('aprendizaje')->nullable();
            $table->string('actitudAula')->nullable();
            $table->string('relacionamiento')->nullable();
            $table->string('testAlteraciones')->nullable();
        });
    }
}
