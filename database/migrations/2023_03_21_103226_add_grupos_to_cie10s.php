<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGruposToCie10s extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cie10s', function (Blueprint $table) {
            $table->string('grupo_relacionado')->nullable();
            $table->string('subgurpo_relacionado')->nullable();
            $table->string('cohorte')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cie10s', function (Blueprint $table) {
            //
        });
    }
}
