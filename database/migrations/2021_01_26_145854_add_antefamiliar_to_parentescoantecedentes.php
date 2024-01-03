<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAntefamiliarToParentescoantecedentes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parentescoantecedentes', function (Blueprint $table) {
            $table->bigInteger('cie10_id')->nullable()->unsigned()->index();
            $table->foreign('cie10_id')->references('id')->on('cie10s');
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
            $table->bigInteger('cie10_id')->nullable()->unsigned()->index();
            $table->foreign('cie10_id')->references('id')->on('cie10s');
        });
    }
}
