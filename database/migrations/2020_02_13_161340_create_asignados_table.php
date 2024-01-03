<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('Permission_id')->unsigned()->index();
            $table->foreign('Permission_id')->references('id')->on('Permissions');
            $table->bigInteger('Pqrsf_id')->unsigned()->index();
            $table->foreign('Pqrsf_id')->references('id')->on('pqrsfs');
            $table->bigInteger('Estado_id')->unsigned()->index();
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
        Schema::dropIfExists('asignados');
    }
}
