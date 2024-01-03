<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoltutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roltutelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('Rol_id')->unsigned()->index();
            $table->foreign('Rol_id')->references('id')->on('Roles');
            $table->bigInteger('Tutela_id')->nullable()->unsigned()->index();
            $table->foreign('Tutela_id')->references('id')->on('Tutelas'); 
            $table->string('Tutela_anterior')->nullable();
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
        Schema::dropIfExists('roltutelas');
    }
}
