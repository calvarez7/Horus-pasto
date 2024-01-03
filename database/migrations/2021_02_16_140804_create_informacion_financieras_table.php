<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformacionFinancierasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informacion_financieras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sarlafs_id')->unsigned()->nullable()->index();
            $table->foreign('sarlafs_id')->references('id')->on('sarlafts');
            $table->string('total_activos')->nullable();
            $table->string('total_pasivos')->nullable();
            $table->string('ingreso_mensual')->nullable();
            $table->string('otros_ingresos')->nullable();
            $table->string('concepto_ingreso')->nullable();
            $table->string('egresos_mensuales')->nullable();
            $table->string('otros_egresos')->nullable();
            $table->string('concepto_egreso')->nullable();
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
        Schema::dropIfExists('informacion_financieras');
    }
}
