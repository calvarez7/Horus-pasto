<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewCie10ToCie10sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cie10s', function (Blueprint $table) {
            $table->bigInteger('estado_id')->nullable()->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
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
            $table->bigInteger('estado_id')->nullable()->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
        });
    }
}
