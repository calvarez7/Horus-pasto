<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasHelpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas_help', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('Permission_id')->unsigned()->index();
            $table->foreign('Permission_id')->references('id')->on('Permissions');
            $table->bigInteger('Estado_id')->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
            $table->string('Nombre');
            $table->string('Descripcion');
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
        Schema::dropIfExists('areas_help');
    }
}
