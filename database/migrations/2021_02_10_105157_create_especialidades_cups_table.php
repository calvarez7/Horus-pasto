<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEspecialidadesCupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especialidades_cups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('especialidad_id')->unsigned()->index();
            $table->foreign('especialidad_id')->references('id')->on('especialidades');
            $table->bigInteger('cup_id')->unsigned()->index();
            $table->foreign('cup_id')->references('id')->on('cups');
            $table->bigInteger('estado_id')->default(1)->unsigned()->index();
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
        Schema::dropIfExists('especialidades_cups');
    }
}
