<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechaSegundaDosisToRegistrocovis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registrocovis', function (Blueprint $table) {
            $table->date('fecha_segunda_dosis')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registrocovis', function (Blueprint $table) {
            $table->date('fecha_segunda_dosis')->nullable();
        });
    }
}
