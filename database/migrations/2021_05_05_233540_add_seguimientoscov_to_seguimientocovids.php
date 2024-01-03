<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeguimientoscovToSeguimientocovids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seguimientocovids', function (Blueprint $table) {
            $table->string('causaseguimiento')->nullable();
            $table->string('tiposeguimiento')->nullable();
            $table->string('esperarencasa')->nullable();
            $table->string('asignarconsulta')->nullable();
            $table->string('seguimientotelefonico')->nullable();
            $table->string('consultaprioritaria')->nullable();
            $table->string('teleorientacion')->nullable();
            $table->string('otrasindicaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seguimientocovids', function (Blueprint $table) {
            $table->string('causaseguimiento')->nullable();
            $table->string('tiposeguimiento')->nullable();
            $table->string('esperarencasa')->nullable();
            $table->string('asignarconsulta')->nullable();
            $table->string('seguimientotelefonico')->nullable();
            $table->string('consultaprioritaria')->nullable();
            $table->string('teleorientacion')->nullable();
            $table->string('otrasindicaciones')->nullable();
        });
    }
}
