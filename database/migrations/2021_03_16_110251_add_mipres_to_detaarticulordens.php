<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMipresToDetaarticulordens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detaarticulordens', function (Blueprint $table) {
            $table->boolean('mipres')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detaarticulordens', function (Blueprint $table) {
            $table->boolean('mipres')->nullable();
        });
    }
}
