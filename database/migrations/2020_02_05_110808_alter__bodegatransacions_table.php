<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBodegatransacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Bodegatransacions', function (Blueprint $table) {
            $table->integer('concentracion_dispensada')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Bodegatransacions', function (Blueprint $table) {
            $table->integer('concentracion_dispensada')->default(0)->nullable();
        });
    }
}
