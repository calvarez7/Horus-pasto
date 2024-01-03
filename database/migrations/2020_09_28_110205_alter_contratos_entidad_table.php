<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterContratosEntidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos', function (Blueprint $table) {
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
        Schema::table('contratos', function (Blueprint $table) {
            $table->bigInteger('entidad_id')->nullable()->unsigned()->index();
            $table->foreign('entidad_id')->references('id')->on('entidades');
        });
    }
}
