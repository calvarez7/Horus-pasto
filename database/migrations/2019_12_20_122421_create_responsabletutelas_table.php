<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponsabletutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsabletutelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('Rol_id')->unsigned()->index();
            $table->foreign('Rol_id')->references('id')->on('Roles');
            $table->bigInteger('Estado_id')->default(1)->unsigned()->index();
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
        Schema::dropIfExists('responsabletutelas');
    }
}
