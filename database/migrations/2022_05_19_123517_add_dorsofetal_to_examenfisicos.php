<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDorsofetalToExamenfisicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examenfisicos', function (Blueprint $table) {
            $table->string('DorsoFetal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('examenfisicos', function (Blueprint $table) {
            $table->string('DorsoFetal')->nullable();
        });
    }
}
