<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConcurrenciaOrdenamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concurrencia_ordenamientos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('registroConcurrencia_id')->unsigned()->index();
            $table->foreign('registroConcurrencia_id')->references('id')->on('registroconcurrencias');
            $table->bigInteger('cup_id')->nullable()->unsigned()->index();
            $table->foreign('cup_id')->references('id')->on('cups');
            $table->string('cantidad');
            $table->bigInteger('user_id')->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('costo');
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
        Schema::dropIfExists('concurrencia_ordenamientos');
    }
}
