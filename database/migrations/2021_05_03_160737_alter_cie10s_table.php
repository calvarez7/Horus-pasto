<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCie10sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cie10s', function (Blueprint $table) {
            $table->boolean('no_quirurgico')->nullable();
            $table->boolean('mortalidad')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cie10s', function (Blueprint $table) {
            $table->boolean('no_quirurgico')->nullable();
            $table->boolean('mortalidad')->nullable();

        });
    }
}
