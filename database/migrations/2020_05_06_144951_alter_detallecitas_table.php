<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDetallecitasTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detallecitas', function (Blueprint $table) {
            $table->bigInteger('cup_id')->nullable()->unsigned()->index();
            $table->foreign('cup_id')->references('id')->on('cups');
            $table->string('lado')->nullable();
            $table->string('tipo_paciente')->nullable();
            $table->string('prioridad')->nullable();
            $table->string('lectura')->nullable();
            $table->string('ubicacion')->nullable();
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
            $table->bigInteger('cup_id')->nullable()->unsigned()->index();
            $table->foreign('cup_id')->references('id')->on('cups');
            $table->string('lado')->nullable();
            $table->string('tipo_paciente')->nullable();
            $table->string('prioridad')->nullable();
            $table->string('lectura')->nullable();
            $table->string('ubicacion')->nullable();
        });
    }
}
