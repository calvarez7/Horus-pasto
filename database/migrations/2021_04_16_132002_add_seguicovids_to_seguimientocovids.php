<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeguicovidsToSeguimientocovids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seguimientocovids', function (Blueprint $table) {
            $table->string('destino_paciente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seguimientocovids', function (Blueprint $table) {
            $table->string('destino_paciente')->nullable();
        });
    }
}
