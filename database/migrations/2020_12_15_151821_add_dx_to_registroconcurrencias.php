<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDxToRegistroconcurrencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registroconcurrencias', function (Blueprint $table) {
           $table->string('dx_concurrencia')->nullable();
            $table->date('fecha_ingreso_concurrencia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registroconcurrencias', function (Blueprint $table) {
           $table->string('dx_concurrencia')->nullable();
            $table->date('fecha_ingreso_concurrencia')->nullable();
        });
    }
}
