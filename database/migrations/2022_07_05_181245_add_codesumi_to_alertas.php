<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodesumiToAlertas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alertas', function (Blueprint $table) {
            $table->bigInteger('codesumis_id')->nullable()->unsigned()->index();
            $table->foreign('codesumis_id')->references('id')->on('codesumis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alertas', function (Blueprint $table) {
            $table->bigInteger('codesumis_id')->nullable()->unsigned()->index();
            $table->foreign('codesumis_id')->references('id')->on('codesumis');
        });
    }
}
