<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUciToRegistrocovis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registrocovis', function (Blueprint $table) {
            $table->string('esperarencasa')->nullable();
            $table->string('asignarconsulta')->nullable();
            $table->string('seguimientotelefonico')->nullable();
            $table->string('consultaprioritaria')->nullable();
            $table->string('teleorientacion')->nullable();
            $table->string('otrasindicaciones')->nullable();
            $table->string('motivoaislamiento')->nullable();
            $table->string('otravivienda')->nullable();
            $table->string('presupuestocomun')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registrocovis', function (Blueprint $table) {
            $table->string('esperarencasa')->nullable();
            $table->string('asignarconsulta')->nullable();
            $table->string('seguimientotelefonico')->nullable();
            $table->string('consultaprioritaria')->nullable();
            $table->string('teleorientacion')->nullable();
            $table->string('otrasindicaciones')->nullable();
            $table->string('motivoaislamiento')->nullable();
            $table->string('otravivienda')->nullable();
            $table->string('presupuestocomun')->nullable();
        });
    }
}
