<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadospqrsfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleadospqrsf', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Empleado_id')->nullable()->unsigned()->index();
            $table->foreign('Empleado_id')->references('id')->on('Empleados');
            $table->bigInteger('Pqrsf_id')->unsigned()->index();
            $table->foreign('Pqrsf_id')->references('id')->on('Pqrsfs');
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
        Schema::dropIfExists('empleadospqrsf');
    }
}
