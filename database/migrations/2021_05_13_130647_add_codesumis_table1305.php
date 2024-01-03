<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodesumisTable1305 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('codesumis', function (Blueprint $table) {
            $table->bigInteger('grupo_id')->nullable()->unsigned()->index();
            $table->foreign('grupo_id')->references('id')->on('grupos');
            $table->bigInteger('grupoterapeutico_id')->nullable()->unsigned()->index();
            $table->foreign('grupoterapeutico_id')->references('id')->on('grupo_terapeuticos');
            $table->bigInteger('subgrupoterapeutico_id')->nullable()->unsigned()->index();
            $table->foreign('subgrupoterapeutico_id')->references('id')->on('subgrupo_terapeuticos');
            $table->string('principio_activo')->nullable();
            $table->bigInteger('formafarmaceutica_id')->nullable()->unsigned()->index();
            $table->foreign('formafarmaceutica_id')->references('id')->on('forma_farmaceuticas');
            $table->float('concentracion1')->nullable();
            $table->string('atc')->nullable();
            $table->text('descripcion_atc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('codesumis', function (Blueprint $table) {
            $table->bigInteger('grupo_id')->nullable()->unsigned()->index();
            $table->foreign('grupo_id')->references('id')->on('grupos');
            $table->bigInteger('grupoterapeutico_id')->nullable()->unsigned()->index();
            $table->foreign('grupoterapeutico_id')->references('id')->on('grupo_terapeuticos');
            $table->bigInteger('subgrupoterapeutico_id')->nullable()->unsigned()->index();
            $table->foreign('subgrupoterapeutico_id')->references('id')->on('subgrupo_terapeuticos');
            $table->string('principio_activo')->nullable();
            $table->bigInteger('formafarmaceutica_id')->nullable()->unsigned()->index();
            $table->foreign('formafarmaceutica_id')->references('id')->on('forma_farmaceuticas');
            $table->float('concentracion1')->nullable();
            $table->string('atc')->nullable();
            $table->text('descripcion_atc')->nullable();
        });
    }
}
