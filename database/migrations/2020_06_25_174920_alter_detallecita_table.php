<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDetallecitaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detallecitas', function (Blueprint $table) {
            $table->string('cama')->nullable();
            $table->string('aislado')->nullable();
            $table->text('observacion_aislado')->nullable();
            $table->string('fecha_orden')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detallecitas', function (Blueprint $table) {
            $table->string('cama')->nullable();
            $table->string('aislado')->nullable();
            $table->text('observacion_aislado')->nullable();
            $table->string('fecha_orden')->nullable();
        });
    }
}
