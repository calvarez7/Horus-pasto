<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEpidemiologicaToCie10sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cie10s', function (Blueprint $table) {
            $table->boolean('ficha_epidemiologica')->default(false)->nullable();
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
            $table->boolean('ficha_epidemiologica')->default(false)->nullable();
        });
    }
}
