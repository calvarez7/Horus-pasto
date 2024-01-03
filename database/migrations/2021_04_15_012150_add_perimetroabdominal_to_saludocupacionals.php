<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerimetroabdominalToSaludocupacionals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saludocupacionals', function (Blueprint $table) {
            $table->float('perimetroabdominal')->nullable();
            $table->integer('presionsistolica')->nullable();
            $table->integer('presiondiastolica')->nullable();
            $table->double('presionarterialmedia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saludocupacionals', function (Blueprint $table) {
            $table->float('perimetroabdominal')->nullable();
            $table->integer('presionsistolica')->nullable();
            $table->integer('presiondiastolica')->nullable();
            $table->double('presionarterialmedia')->nullable();
        });
    }
}
