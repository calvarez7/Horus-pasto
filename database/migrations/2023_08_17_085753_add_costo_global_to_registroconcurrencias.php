<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostoGlobalToRegistroconcurrencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registroconcurrencias', function (Blueprint $table) {
            $table->string('costo_total_global')->nullable();
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
            $table->string('costo_total_global')->nullable();
        });
    }
}
