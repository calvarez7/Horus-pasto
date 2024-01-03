<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiomicroscopiaOftalmoscopiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biomicroscopia_oftalmoscopias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('biomicroscopiaOd')->nullable();
            $table->string('biomicroscopiaOi')->nullable();
            $table->string('pioOd')->nullable();
            $table->string('pioOi')->nullable();
            $table->string('oftalmoscopiaOd')->nullable();
            $table->string('oftalmoscopiaOi')->nullable();
            $table->bigInteger('citapaciente_id')->nullable()->index();
            $table->foreign('citapaciente_id')->references('id')->on('cita_paciente');
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
        Schema::dropIfExists('biomicroscopia_oftalmoscopias');
    }
}
