<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCopagosToConfiguraciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuraciones', function (Blueprint $table) {
            $table->float('porcentaje_copago_nivel_1')->nullable();
            $table->float('porcentaje_copago_nivel_2')->nullable();
            $table->float('porcentaje_copago_nivel_3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuraciones', function (Blueprint $table) {
            $table->float('porcentaje_copago_nivel_1')->nullable();
            $table->float('porcentaje_copago_nivel_2')->nullable();
            $table->float('porcentaje_copago_nivel_3')->nullable();
        });
    }
}
