<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPaqRipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paq_rips', function (Blueprint $table) {
            $table->boolean('partial')->nullable();
            $table->text('motivo')->nullable();
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
        Schema::table('paq_rips', function (Blueprint $table) {
            $table->boolean('partial')->nullable();
            $table->bigInteger('estado_id')->nullable()->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
        });
    }
}
