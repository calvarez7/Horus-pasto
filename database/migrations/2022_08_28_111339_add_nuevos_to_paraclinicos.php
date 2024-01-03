<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNuevosToParaclinicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paraclinicos', function (Blueprint $table) {
            $table->string('nombreParaclinico')->nullable();
            $table->double('resultadoParaclinico')->nullable();
            $table->boolean('checkboxParaclinicos')->nullable();
            $table->date('fechaParaclinico')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paraclinicos', function (Blueprint $table) {
            $table->string('nombreParaclinico')->nullable();
            $table->double('resultadoParaclinico')->nullable();
            $table->boolean('checkboxParaclinicos')->nullable();
            $table->date('fechaParaclinico')->nullable();
        });
    }
}
