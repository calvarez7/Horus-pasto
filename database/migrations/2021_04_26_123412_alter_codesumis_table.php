<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCodesumisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('codesumis', function (Blueprint $table) {
            $table->float('concentracion2')->nullable();
            $table->float('concentracion3')->nullable();
            $table->string('unidad_concentracion')->nullable();
            $table->float('contenido')->nullable();
            $table->string('unidad_contenido')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('codesumis', function (Blueprint $table) {
            $table->float('concentracion2')->nullable();
            $table->float('concentracion3')->nullable();
            $table->string('unidad_concentracion')->nullable();
            $table->float('contenido')->nullable();
            $table->string('unidad_contenido')->nullable();
        });
    }
}
