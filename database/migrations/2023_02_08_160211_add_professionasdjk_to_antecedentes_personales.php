<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfessionasdjkToAntecedentesPersonales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('antecedentes_personales', function (Blueprint $table) {
            $table->string('descricion_demanda_inducida')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('antecedentes_personales', function (Blueprint $table) {
            $table->string('descricion_demanda_inducida')->nullable();
        });
    }
}
