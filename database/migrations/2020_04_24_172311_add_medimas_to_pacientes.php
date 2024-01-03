<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMedimasToPacientes1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->integer('nivel')->nullable();
            $table->bigInteger('entidad_id')->nullable()->unsigned()->index();
            $table->foreign('entidad_id')->references('id')->on('entidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->integer('nivel')->nullable();
            $table->bigInteger('entidad_id')->nullable()->unsigned()->index();
            $table->foreign('entidad_id')->references('id')->on('entidades');
        });
    }
}
