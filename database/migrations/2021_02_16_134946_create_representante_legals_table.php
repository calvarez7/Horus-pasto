<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepresentanteLegalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('representante_legals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sarlafs_id')->unsigned()->nullable()->index();
            $table->foreign('sarlafs_id')->references('id')->on('sarlafts');
            $table->string('nombre')->nullable();
            $table->string('tipo_doc')->nullable();
            $table->string('num_doc')->nullable();
            $table->string('fecha_exp')->nullable();
            $table->string('fecha_nac')->nullable();
            $table->bigInteger('lugar_nac')->unsigned()->nullable()->index();
            $table->foreign('lugar_nac')->references('id')->on('municipios');
            $table->bigInteger('lugar_exp')->unsigned()->nullable()->index();
            $table->foreign('lugar_exp')->references('id')->on('municipios');
            $table->string('otra_nacionalidad')->nullable();
            $table->string('emali')->nullable();
            $table->bigInteger('ciudad_reci')->unsigned()->nullable()->index();
            $table->foreign('ciudad_reci')->references('id')->on('departamentos');
            $table->bigInteger('deparamento_reci')->unsigned()->nullable()->index();
            $table->foreign('deparamento_reci')->references('id')->on('departamentos');
            $table->string('direccion_reci')->nullable();
            $table->string('pais_reci')->nullable();
            $table->string('telefono')->nullable();
            $table->string('cargo_publico')->nullable();
            $table->string('poder_publico')->nullable();
            $table->string('reconocimento_publico')->nullable();
            $table->string('ocupacion')-> nullable();
            $table->string('donde_trabaja')->nullable();
            $table->string('obli_tibutarias')->nullable();
            $table->string('descripcion_obliga')->nullable();
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
        Schema::dropIfExists('representante_legals');
    }
}
