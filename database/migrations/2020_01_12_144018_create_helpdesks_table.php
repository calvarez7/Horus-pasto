<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHelpdesksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helpdesks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Actividad_id')->unsigned()->index();
            $table->foreign('Actividad_id')->references('id')->on('Actividades_help');
            $table->bigInteger('Estado_id')->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
            $table->string('Cargo_user');
            $table->string('Sede');
            $table->string('Requerimiento');
            $table->string('Asunto');
            $table->text('Descripcion');
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
        Schema::dropIfExists('helpdesk');
    }
}
