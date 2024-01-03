<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEntidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entidades', function (Blueprint $table) {
            $table->boolean('agendar_paciente')->nullable();
            $table->boolean('entrega_medicamentos')->nullable();
            $table->boolean('atender_paciente')->nullable();
            $table->boolean('autorizar_ordenes')->nullable();
            $table->boolean('consutar_historico')->nullable();
            $table->boolean('generar_ordenes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entidades', function (Blueprint $table) {
            $table->boolean('agendar_paciente')->nullable();
            $table->boolean('entrega_medicamentos')->nullable();
            $table->boolean('atender_paciente')->nullable();
            $table->boolean('autorizar_ordenes')->nullable();
            $table->boolean('consutar_historico')->nullable();
            $table->boolean('generar_ordenes')->nullable();
        });
    }
}
