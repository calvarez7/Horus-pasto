<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCalificacionToHelpdesks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('helpdesks', function (Blueprint $table) {
            $table->string('calificacion')->nullable();
            $table->date('tiempo_estimado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('helpdesks', function (Blueprint $table) {
            $table->string('calificacion')->nullable();
            $table->date('tiempo_estimado')->nullable();
        });
    }
}
