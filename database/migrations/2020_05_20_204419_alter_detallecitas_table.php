<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDetallecitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detallecitas', function (Blueprint $table) {
            $table->text('observa_admision')->nullable();
            $table->string('tecnica')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detallecitas', function (Blueprint $table) {
            $table->text('observa_admision')->nullable();
            $table->string('tecnica')->nullable();
        });
    }
}
