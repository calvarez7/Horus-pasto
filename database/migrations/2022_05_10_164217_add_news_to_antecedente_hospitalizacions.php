<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewsToAntecedenteHospitalizacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('antecedente_hospitalizacions', function (Blueprint $table) {
            $table->string('descripcionneonatal')->nullable();
            $table->string('descripcionurgencias')->nullable();
            $table->string('descripcionhospiurg')->nullable();
            $table->string('descripcion_urgencias_mas_tres')->nullable();
            $table->string('descripcion_urgencias_mas_tres_semanas')->nullable();
            $table->string('descripcion_hospi_uci')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('antecedente_hospitalizacions', function (Blueprint $table) {
            $table->string('descripcionneonatal')->nullable();
            $table->string('descripcionurgencias')->nullable();
            $table->string('descripcionhospiurg')->nullable();
            $table->string('descripcion_urgencias_mas_tres')->nullable();
            $table->string('descripcion_urgencias_mas_tres_semanas')->nullable();
            $table->string('descripcion_hospi_uci')->nullable();
        });
    }
}
