<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNuevosToAntecedenteBiopsicosocials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('antecedente_biopsicosocials', function (Blueprint $table) {
            $table->string('descripcion_actividad')->nullable();
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
            $table->string('descripcion_actividad')->nullable();
        });
    }
}
