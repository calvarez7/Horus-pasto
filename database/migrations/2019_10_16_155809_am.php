<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Am extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Af_id')->unsigned()->index();
            $table->foreign('Af_id')->references('id')->on('afs')->Ondelete('cascade');
            $table->bigInteger('Us_id')->unsigned()->index();
            $table->foreign('Us_id')->references('id')->on('us')->Ondelete('cascade');
            $table->string('Numero_Autorizacion')->nullable();
            $table->bigInteger('Cum_id')->unsigned()->index();
            $table->foreign('Cum_id')->references('id')->on('cums')->Ondelete('cascade');
            $table->string('Tipo_Medicamento')->nullable();
            $table->string('Numero_Unidades')->nullable();
            $table->string('Valorunitario_Medecamento')->nullable();
            $table->string('Valortotal_Medecamento')->nullable();
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
        Schema::dropIfExists('ams');
    }
}
