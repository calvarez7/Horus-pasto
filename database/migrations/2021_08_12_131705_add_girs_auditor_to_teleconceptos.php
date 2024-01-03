<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGirsAuditorToTeleconceptos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teleconceptos', function (Blueprint $table) {
            $table->boolean('girs_auditor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teleconceptos', function (Blueprint $table) {
            $table->boolean('girs_auditor')->nullable();
        });
    }
}
