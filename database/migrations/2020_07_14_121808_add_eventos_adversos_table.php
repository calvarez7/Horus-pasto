<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventosAdversosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eventos_adversos', function (Blueprint $table) {
            $table->bigInteger('tipo_id')->unsigned()->nullable()->index();
            $table->foreign('tipo_id')->references('id')->on('tipos');
            $table->bigInteger('detallearticulo_id')->unsigned()->nullable()->index();
            $table->foreign('detallearticulo_id')->references('id')->on('detallearticulos');
            $table->bigInteger('estado_id')->nullable()->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eventos_adversos', function (Blueprint $table) {
            $table->bigInteger('tipo_id')->unsigned()->nullable()->index();
            $table->foreign('tipo_id')->references('id')->on('tipos');
            $table->bigInteger('detallearticulo_id')->unsigned()->nullable()->index();
            $table->foreign('detallearticulo_id')->references('id')->on('detallearticulos');
            $table->bigInteger('estado_id')->nullable()->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
        });
    }
}
