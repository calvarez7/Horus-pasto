<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsignadoCmedicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignado_cmedicas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('permission_id')->unsigned()->index();
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->bigInteger('af_id')->unsigned()->index();
            $table->foreign('af_id')->references('id')->on('afs');
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
        Schema::dropIfExists('asignado_cmedicas');
    }
}
