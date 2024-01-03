<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDomicilioToDetaarticulordens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detaarticulordens', function (Blueprint $table) {
            $table->boolean('adomicilio')->nullable();
            $table->string('domiciliario')->nullable();
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
            $table->boolean('adomicilio')->nullable();
            $table->string('domiciliario')->nullable();
        });
    }
}
 