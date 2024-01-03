<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('num_doc')->nullable();
            $table->string('tipo_doc')->nullable();
            $table->string('full_name')->nullable();
            $table->string('id_estadoafiliado')->nullable();
            $table->string('id_tipoafiliado')->nullable();
            $table->integer('id_ipsptoAtencion')->nullable();
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
        Schema::dropIfExists('certificados');
    }
}
