<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmRipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('am_rips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('orden_id')->nullable();
            $table->string('paciente_id')->nullable();
            $table->string('cod_habilitacion_sede')->nullable();
            $table->string('tipo_doc')->nullable();
            $table->string('numero_doc')->nullable();
            $table->string('numero_autorizacion')->nullable();
            $table->string('cod_cum')->nullable();
            $table->string('tipo_medicamento')->nullable();
            $table->string('nombre_medicamento')->nullable();
            $table->string('forma_farmaceutica')->nullable();
            $table->string('concentracion')->nullable();
            $table->string('unidad_medida')->nullable();
            $table->string('cantidad')->nullable();
            $table->string('valor_unitario')->nullable();
            $table->string('valor_total')->nullable();
            $table->string('entidad_id')->nullable();
            $table->string('f_dispensacion')->nullable();
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
        Schema::dropIfExists('am_rips');
    }
}
