<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegrantesJuntaGirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrantes_junta_girs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('teleconcepto_id')->nullable()->unsigned()->index();
            $table->foreign('teleconcepto_id')->references('id')->on('teleconceptos');
            $table->bigInteger('usuario_id')->nullable()->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('users');
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
        Schema::dropIfExists('integrantes_junta_girs');
    }
}
