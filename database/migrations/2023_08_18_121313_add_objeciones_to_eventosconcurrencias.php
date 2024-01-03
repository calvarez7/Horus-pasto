<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObjecionesToEventosconcurrencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eventosconcurrencias', function (Blueprint $table) {
            $table->string('objeciones')->nullable();
            $table->string('tipo_altaTemprana')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eventosconcurrencias', function (Blueprint $table) {
            $table->string('objeciones')->nullable();
            $table->string('tipo_altaTemprana')->nullable();
        });
    }
}
