<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFamiliarToParentescoantecedentes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parentescoantecedentes', function (Blueprint $table) {
            $table->integer('parentesco_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parentescoantecedentes', function (Blueprint $table) {
            $table->integer('parentesco_id')->nullable();
        });
    }
}
