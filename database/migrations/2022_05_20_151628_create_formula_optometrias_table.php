<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulaOptometriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formula_optometrias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('esfera_od')->nullable();
            $table->string('esfera_oi')->nullable();
            $table->string('cilindro_od')->nullable();
            $table->string('cilindro_oi')->nullable();
            $table->string('eje_od')->nullable();
            $table->string('eje_oi')->nullable();
            $table->string('adicion_od')->nullable();
            $table->string('adicion_oi')->nullable();
            $table->string('prisma_od')->nullable();
            $table->string('prisma_oi')->nullable();
            $table->string('grados_od')->nullable();
            $table->string('grados_oi')->nullable();
            $table->string('lejos_od')->nullable();
            $table->string('lejos_oi')->nullable();
            $table->string('cerca_od')->nullable();
            $table->string('cerca_oi')->nullable();
            $table->string('tipo_lente')->nullable();
            $table->string('detalle')->nullable();
            $table->string('altura')->nullable();
            $table->string('color')->nullable();
            $table->string('dp')->nullable();
            $table->string('dispositivos')->nullable();
            $table->string('control')->nullable();
            $table->string('vigencia')->nullable();
            $table->string('observacion')->nullable();
            $table->bigInteger('usuario_id')->nullable()->index();
            $table->foreign('usuario_id')->references('id')->on('Users');
            $table->bigInteger('Orden_id')->unsigned()->index();
            $table->foreign('Orden_id')->references('id')->on('Ordens');
            $table->bigInteger('Estado_id')->default('1')->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
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
        Schema::dropIfExists('formula_optometrias');
    }
}
