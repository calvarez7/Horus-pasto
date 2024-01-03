<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddControlToSeguimientocovids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seguimientocovids', function (Blueprint $table) {
            $table->string('oxigenoterapia')->nullable();
            $table->string('flujo_oxigeno')->nullable();
            $table->string('control')->nullable();
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
            $table->string('oxigenoterapia')->nullable();
            $table->string('flujo_oxigeno')->nullable();
            $table->string('control')->nullable();
        });
    }
}
