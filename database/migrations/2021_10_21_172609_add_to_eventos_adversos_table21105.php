<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToEventosAdversosTable21105 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eventos_adversos', function (Blueprint $table) {
            $table->string('voluntariedad')->nullable();
            $table->string('profesional')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eventos_adversos', function (Blueprint $table) {
            $table->string('voluntariedad')->nullable();
            $table->string('profesional')->nullable();
        });
    }
}
