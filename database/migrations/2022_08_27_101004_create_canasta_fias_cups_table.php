<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCanastaFiasCupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canasta_fias_cups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cup');
            $table->string('grupo');
            $table->string('archivo_rips');
            $table->string('fias');
            $table->integer('tratamiento_actual_f5')->nullable();
            $table->bigInteger('estado_id')->default('1')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('Estados');
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
        Schema::dropIfExists('canasta_fias_cups');
    }
}
