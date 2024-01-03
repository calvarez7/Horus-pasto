<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInsulinaToCodesumisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('codesumis', function (Blueprint $table) {
            $table->boolean("insulina")->nullable()->default(0);
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
            $table->boolean("insulina")->nullable()->default(0);
        });
    }
}
