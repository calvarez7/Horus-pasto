<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNuevosToConductas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conductas', function (Blueprint $table) {
            $table->string('cursoprofilactico')->nullable();
            $table->string('asesoriaive')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conductas', function (Blueprint $table) {
            $table->string('cursoprofilactico')->nullable();
            $table->string('asesoriaive')->nullable();
        });
    }
}
