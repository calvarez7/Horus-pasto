<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEsquemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('esquemas', function (Blueprint $table) {
            $table->string('nombreEsquema')->nullable();
            $table->string('abrevEsquema')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('esquemas', function (Blueprint $table) {
            $table->string('nombreEsquema')->nullable();
            $table->string('abrevEsquema')->nullable();
        });
    }
}
