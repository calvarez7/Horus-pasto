<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDaysToOrdensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordens', function (Blueprint $table) {
            //
            $table->string('scheme_name')->nullable();
            $table->string('page')->nullable();
            $table->string('total_pages')->nullable();
            $table->string('day')->nullable();
            $table->string('repetition_frequency')->nullable();
            $table->string('biography')->nullable();
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
