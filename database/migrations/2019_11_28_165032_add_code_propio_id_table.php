<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodePropioIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cupordens', function (Blueprint $table) {
            $table->bigInteger('Codepropio_id')->nullable()->unsigned()->index();
            $table->foreign('Codepropio_id')->references('id')->on('codepropios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
