<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEspecialidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especialidades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Nombre');
            $table->bigInteger('Primeravez_id')->nullable()->unsigned()->index();
            $table->foreign('Primeravez_id')->references('id')->on('Cups');
            $table->bigInteger('Control_id')->nullable()->unsigned()->index();
            $table->foreign('Control_id')->references('id')->on('Cups');
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
        Schema::dropIfExists('especialidades');
    }
}
