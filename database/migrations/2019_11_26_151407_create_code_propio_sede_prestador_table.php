<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodePropioSedePrestadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_propio_sede_prestador', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Ambito')->nullable();
            $table->integer('Valor');
            $table->bigInteger('codepropio_id')->unsigned()->index();
            $table->foreign('codepropio_id')->references('id')->on('codepropios');
            $table->bigInteger('sedeproveedor_id')->unsigned()->index();
            $table->foreign('sedeproveedor_id')->references('id')->on('sedeproveedores');
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
        Schema::dropIfExists('code_propio_sede_prestador');
    }
}
