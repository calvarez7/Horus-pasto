<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChecbosGinecosToGinecologicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Ginecologicos', function (Blueprint $table) {
            $table->boolean('checkboxFechaultimamenstruacion')->nullable();
            $table->boolean('checkboxGestaciones')->nullable();
            $table->boolean('checkboxPartos')->nullable();
            $table->boolean('checkboxAbortoprovocado')->nullable();
            $table->boolean('checkboxAbortoespontaneo')->nullable();
            $table->boolean('checkboxMortinato')->nullable();
            $table->boolean('checkboxGestante')->nullable();
            $table->boolean('checkboxEutoxico')->nullable();
            $table->boolean('checkboxCesaria')->nullable();
            $table->boolean('checkboxCancercuellouterino')->nullable();
            $table->boolean('checkboxCitologia')->nullable();
            $table->boolean('checkboxMenarquia')->nullable();
            $table->boolean('checkboxCiclos')->nullable();
            $table->boolean('checkboxRegulares')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Ginecologicos', function (Blueprint $table) {
            $table->boolean('checkboxFechaultimamenstruacion')->nullable();
            $table->boolean('checkboxGestaciones')->nullable();
            $table->boolean('checkboxPartos')->nullable();
            $table->boolean('checkboxAbortoprovocado')->nullable();
            $table->boolean('checkboxAbortoespontaneo')->nullable();
            $table->boolean('checkboxMortinato')->nullable();
            $table->boolean('checkboxGestante')->nullable();
            $table->boolean('checkboxEutoxico')->nullable();
            $table->boolean('checkboxCesaria')->nullable();
            $table->boolean('checkboxCancercuellouterino')->nullable();
            $table->boolean('checkboxCitologia')->nullable();
            $table->boolean('checkboxMenarquia')->nullable();
            $table->boolean('checkboxCiclos')->nullable();
            $table->boolean('checkboxRegulares')->nullable();
        });
    }
}
