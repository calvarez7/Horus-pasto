<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransplantadosFiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transplantados_fias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('grupo_lgtbiq')->nullable();
            $table->string('pertenencia_etnica')->nullable();
            $table->string('grupo_poblacional')->nullable();
            $table->string('cie_10_del_diagnostico')->nullable();
            $table->string('descripcion_del_cie_10')->nullable();
            $table->string('el_usuario_tiene_mas_de_1_trasplante')->nullable();
            $table->string('fecha_de_solicitud_de_trasplante')->nullable();
            $table->string('fecha_de_trasplante')->nullable();
            $table->string('fecha_ùltimo_control')->nullable();
            $table->string('novedad')->nullable();
            $table->string('fecha_de_desafiliaciòn')->nullable();
            $table->string('fecha_de_muerte')->nullable();
            $table->string('paciente_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transplantados_fias');
    }
}
