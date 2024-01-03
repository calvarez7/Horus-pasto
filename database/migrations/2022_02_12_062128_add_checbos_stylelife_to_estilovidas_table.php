<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChecbosStylelifeToEstilovidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estilovidas', function (Blueprint $table) {
            $table->boolean('checkboxDietasaludable')->nullable();
            $table->boolean('checkboxSuenoreparador')->nullable();
            $table->boolean('checkboxDuermemenoseishoras')->nullable();
            $table->boolean('checkboxAltonivelestres')->nullable();
            $table->boolean('checkboxActividadfisica')->nullable();
            $table->boolean('checkboxFuma')->nullable();
            $table->boolean('checkboxTomalicor')->nullable();
            $table->boolean('checkboxConsumopsicoactivo')->nullable();
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
            $table->boolean('checkboxDietasaludable')->nullable();
            $table->boolean('checkboxSuenoreparador')->nullable();
            $table->boolean('checkboxDuermemenoseishoras')->nullable();
            $table->boolean('checkboxAltonivelestres')->nullable();
            $table->boolean('checkboxActividadfisica')->nullable();
            $table->boolean('checkboxFuma')->nullable();
            $table->boolean('checkboxTomalicor')->nullable();
            $table->boolean('checkboxConsumopsicoactivo')->nullable();
        });
    }
}
