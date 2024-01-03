<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Alter3TeleconceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teleconceptos', function (Blueprint $table) {
            $table->dropColumn('no_pertinente_teleconcepto');
            $table->dropColumn('observacion_pertinente_teleconcepto');
            $table->dropColumn('no_pertinente_ordenamiento');
            $table->dropColumn('observacion_pertinente_ordenamiento');
            $table->string('pertinencia_prioridad')->nullable();
            $table->string('pertinencia_evaluacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teleconceptos', function (Blueprint $table) {
            $table->dropColumn('no_pertinente_teleconcepto');
            $table->dropColumn('observacion_pertinente_teleconcepto');
            $table->dropColumn('no_pertinente_ordenamiento');
            $table->dropColumn('observacion_pertinente_ordenamiento');
            $table->string('pertinencia_prioridad')->nullable();
            $table->string('pertinencia_evaluacion')->nullable();
        });
    }
}
