<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdencompras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordencompras', function (Blueprint $table) {
            $table->string('lote')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->bigInteger('usuario_ingresa')->unsigned()->index()->nullable();
            $table->foreign('usuario_ingresa')->references('id')->on('Users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordencompras', function (Blueprint $table) {
            $table->string('lote')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->bigInteger('usuario_ingresa')->unsigned()->index()->nullable();
            $table->foreign('usuario_ingresa')->references('id')->on('Users');
        });
    }
}
