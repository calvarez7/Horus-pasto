<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaqTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paq_temps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('namePaq')->nullable();
            $table->string('reason')->nullable();
            $table->string('repName')->nullable();
            $table->string('totalValueAf')->nullable();
            $table->string('totalBills')->nullable();
            $table->string('code')->nullable();
            $table->bigInteger('Estado_id')->nullable()->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
            $table->bigInteger('creatorUser_id')->unsigned()->index();
            $table->foreign('creatorUser_id')->references('id')->on('Users');
            $table->bigInteger('auditorUser_id')->nullable()->unsigned()->index();
            $table->foreign('auditorUser_id')->references('id')->on('Users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paq_temps');
    }
}
