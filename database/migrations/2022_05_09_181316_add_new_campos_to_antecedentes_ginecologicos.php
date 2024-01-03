<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewCamposToAntecedentesGinecologicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('antecedentes_ginecologicos', function (Blueprint $table) {
            $table->string('ProcedimientosMenores')->nullable();
            $table->string('edad')->nullable();
            $table->string('fecha_ultimaMenstruacion')->nullable();
            $table->boolean('checkboxPrimerarelacion')->nullable();
            $table->string('EdadPrimera')->nullable();
            $table->boolean('checkboxCitologia')->nullable();
            $table->string('fecha_citologia')->nullable();
            $table->string('resultado_citologia')->nullable();
            $table->boolean('checkboxMamografia')->nullable();
            $table->string('fecha_mamografia')->nullable();
            $table->string('resultado_mamografia')->nullable();
            $table->boolean('checkbox_gestante')->nullable();
            $table->string('fecha_probable')->nullable();
            $table->string('descripcioneco1')->nullable();
            $table->string('fecha_segundaeco')->nullable();
            $table->boolean('checkboxEco1')->nullable();
            $table->string('fecha_pimeraeco')->nullable();
            $table->boolean('checkboxEco2')->nullable();
            $table->string('descripcioneco2')->nullable();
            $table->boolean('checkboxEco3')->nullable();
            $table->string('fecha_terceraeco')->nullable();
            $table->string('descripcioneco3')->nullable();
            $table->string('periodo_interginesico')->nullable();
            $table->string('descripcion_interginesico_corto')->nullable();
            $table->string('descripcion_interginesico_largo')->nullable();
            $table->boolean('checkbox_puerpera')->nullable();
            $table->string('violencia1')->nullable();
            $table->string('violencia2')->nullable();
            $table->string('violencia3')->nullable();
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
            $table->string('ProcedimientosMenores')->nullable();
            $table->string('edad')->nullable();
            $table->string('fecha_ultimaMenstruacion')->nullable();
            $table->boolean('checkboxPrimerarelacion')->nullable();
            $table->string('EdadPrimera')->nullable();
            $table->boolean('checkboxCitologia')->nullable();
            $table->string('fecha_citologia')->nullable();
            $table->string('resultado_citologia')->nullable();
            $table->boolean('checkboxMamografia')->nullable();
            $table->string('fecha_mamografia')->nullable();
            $table->string('resultado_mamografia')->nullable();
            $table->boolean('checkbox_gestante')->nullable();
            $table->string('fecha_probable')->nullable();
            $table->string('descripcioneco1')->nullable();
            $table->string('fecha_segundaeco')->nullable();
            $table->boolean('checkboxEco1')->nullable();
            $table->string('fecha_pimeraeco')->nullable();
            $table->boolean('checkboxEco2')->nullable();
            $table->string('descripcioneco2')->nullable();
            $table->boolean('checkboxEco3')->nullable();
            $table->string('fecha_terceraeco')->nullable();
            $table->string('descripcioneco3')->nullable();
            $table->string('periodo_interginesico')->nullable();
            $table->string('descripcion_interginesico_corto')->nullable();
            $table->string('descripcion_interginesico_largo')->nullable();
            $table->boolean('checkbox_puerpera')->nullable();
            $table->string('violencia1')->nullable();
            $table->string('violencia2')->nullable();
            $table->string('violencia3')->nullable();
        });
    }
}
