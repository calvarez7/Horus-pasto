<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Ap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Af_id')->unsigned()->index();
            $table->foreign('Af_id')->references('id')->on('afs')->OnDelete('cascade');
            $table->bigInteger('Us_id')->unsigned()->index();
            $table->foreign('Us_id')->references('id')->on('us')->OnDelete('cascade');
            $table->string('Fecha_Procedimiento');
            $table->string('Numero_Autorizacion')->nullable();
            $table->bigInteger('Codigo_Procedimiento')->unsigned()->index();
            $table->foreign('Codigo_Procedimiento')->references('id')->on('cups')->OnDelete('cascade');
            $table->string('Ambito_Procedimiento');
            $table->string('Finalidad_Procedimiento');
            $table->string('Personal_Atiende');
            $table->bigInteger('Diagnostico_Principal')->unsigned()->index();
            $table->foreign('Diagnostico_Principal')->references('id')->on('cie10s')->OnDelete('cascade');
            $table->string('Diagnostico_Relacionado')->nullable();
            $table->string('Complicacion')->nullable();
            $table->string('Acto_Quirurgico');
            $table->string('Valor_Procedimiento');
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
        Schema::dropIfExists('aps');
    }
}
