<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesResolucionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_resolucion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('resolucion_1552_id')->unsigned()->nullable()->index();
            $table->foreign('resolucion_1552_id')->references('id')->on('resolucion_1552');
            $table->string('mes')->nullable();
            $table->string('usuario')->nullable();
            $table->string('tipo_identificacion')->nullable();
            $table->string('numero_identificacion')->nullable();
            $table->bigInteger('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ips_primaria')->nullable();
            $table->string('departamento')->nullable();
            $table->string('servicio')->nullable();
            $table->Integer('tipo_consulta')->nullable();
            $table->string('EAPB')->nullable();
            $table->string('contratista')->nullable();
            $table->datetime('solicita_cita')->nullable();
            $table->datetime('solicita_usuario')->nullable();
            $table->datetime('asgina_cita')->nullable();
            $table->string('ips_responsable')->nullable();
            $table->bigInteger('codigo_habilitacion')->nullable();
            $table->bigInteger('estado_id')->default('1')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
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
        Schema::dropIfExists('detalles_resolucion');
    }
}
