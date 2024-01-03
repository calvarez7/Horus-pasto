<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFelicitacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('felicitaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('felcitacion')->nullable();
            $table->bigInteger('empleado_id')->unsigned()->index();
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('felicitaciones');
    }
}
