<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodesumiTable2505 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('codesumis', function (Blueprint $table) {
            $table->bigInteger('linea_base_id')->nullable()->unsigned()->index();
            $table->foreign('linea_base_id')->references('id')->on('linea_bases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('codesumis', function (Blueprint $table) {
            $table->bigInteger('linea_base_id')->nullable()->unsigned()->index();
            $table->foreign('linea_base_id')->references('id')->on('linea_bases');
        });
    }
}
