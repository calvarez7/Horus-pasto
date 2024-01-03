<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSedeproveToBodegas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bodegas', function (Blueprint $table) {
            $table->bigInteger('sedeprestador_id')->nullable()->unsigned()->index();
            $table->foreign('sedeprestador_id')->references('id')->on('sedeproveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bodegas', function (Blueprint $table) {
            $table->bigInteger('sedeprestador_id')->nullable()->unsigned()->index();
            $table->foreign('sedeprestador_id')->references('id')->on('sedeproveedores');
        });
    }
}
