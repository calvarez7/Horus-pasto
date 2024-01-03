<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTutelasTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutelas', function (Blueprint $table) {
            $table->bigInteger('Quiencerro_id')->nullable()->unsigned()->index();
            $table->foreign('Quiencerro_id')->references('id')->on('Users');
            $table->text('Motivo_cerrar')->nullable();
            $table->date('Fecha_cerrada')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tutelas', function (Blueprint $table) {
            $table->bigInteger('Quiencerro_id')->nullable()->unsigned()->index();
            $table->foreign('Quiencerro_id')->references('id')->on('Users');
            $table->text('Motivo_cerrada')->nullable();
            $table->date('Fecha_cerrada')->nullable();
        });
    }
}
