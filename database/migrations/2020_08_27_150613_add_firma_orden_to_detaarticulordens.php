<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFirmaOrdenToDetaarticulordens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detaarticulordens', function (Blueprint $table) {
            $table->string('firma_orden')->nullable();
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
            $table->string('firma_orden')->nullable();
        });
    }
}
