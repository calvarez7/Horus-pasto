<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEventosAdversosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eventos_adversos', function (Blueprint $table) {
            $table->bigInteger('clasificacionevento_id')->unsigned()->nullable()->index();
            $table->foreign('clasificacionevento_id')->references('id')->on('clasificacion_eventos');
            $table->bigInteger('tipoevento_id')->unsigned()->nullable()->index();
            $table->foreign('tipoevento_id')->references('id')->on('tipo_eventos');
            $table->string('clasificacion_invima')->nullable();
            $table->string('dosis_medicamento')->nullable();
            $table->string('medida_medicamento')->nullable();
            $table->string('via_medicamento')->nullable();
            $table->string('frecuencia_medicamento')->nullable();
            $table->string('infusion_medicamento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eventos_adversos', function (Blueprint $table) {
            $table->bigInteger('clasificacionevento_id')->unsigned()->nullable()->index();
            $table->foreign('clasificacionevento_id')->references('id')->on('clasificacion_eventos');
            $table->bigInteger('tipoevento_id')->unsigned()->nullable()->index();
            $table->foreign('tipoevento_id')->references('id')->on('tipo_eventos');
            $table->string('clasificacion_invima')->nullable();
            $table->string('dosis_medicamento')->nullable();
            $table->string('medida_medicamento')->nullable();
            $table->string('via_medicamento')->nullable();
            $table->string('frecuencia_medicamento')->nullable();
            $table->string('infusion_medicamento')->nullable();
        });
    }
}
