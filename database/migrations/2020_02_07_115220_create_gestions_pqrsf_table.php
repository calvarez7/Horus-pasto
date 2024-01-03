<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGestionsPqrsfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gestions_pqrsf', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Pqrsf_id')->unsigned()->index();
            $table->foreign('Pqrsf_id')->references('id')->on('pqrsfs');
            $table->bigInteger('User_id')->nullable()->unsigned()->index();
            $table->foreign('User_id')->references('id')->on('users');
            $table->bigInteger('Paciente_id')->nullable()->unsigned()->index();
            $table->foreign('Paciente_id')->references('id')->on('pacientes');
            $table->bigInteger('Tipo_id')->unsigned()->index();
            $table->foreign('Tipo_id')->references('id')->on('Tipos');
            $table->text('Motivo');
            $table->string('Responsable')->nullable();
            $table->date('Fecha')->nullable();
            $table->string('Medio')->nullable();
            $table->string('Aquien_not')->nullable();
            $table->string('Parentesco')->nullable();
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
        Schema::dropIfExists('gestions_pqrsf');
    }
}
