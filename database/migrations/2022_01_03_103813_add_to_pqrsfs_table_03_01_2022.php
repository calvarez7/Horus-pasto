<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToPqrsfsTable03012022 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pqrsfs', function (Blueprint $table) {
            $table->text('derecho')->nullable();
            $table->text('deber')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pqrsfs', function (Blueprint $table) {
            $table->text('derecho')->nullable();
            $table->text('deber')->nullable();
        });
    }
}
