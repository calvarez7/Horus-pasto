<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoltutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roltutelas', function (Blueprint $table) {
            $table->bigInteger('Estado_id')->nullable()->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
            $table->dropColumn('Tutela_anterior');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roltutelas', function (Blueprint $table) {
            $table->bigInteger('Estado_id')->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
            $table->dropColumn('Tutela_anterior');
        });
    }
}
