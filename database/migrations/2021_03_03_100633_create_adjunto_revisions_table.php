<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjuntoRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjunto_revisions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('revision_sarlafts_id')->unsigned()->nullable()->index();
            $table->foreign('revision_sarlafts_id')->references('id')->on('revision_sarlafts');
            $table->string('nombre')->nullable();
            $table->string('ruta');
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
        Schema::dropIfExists('adjunto_revisions');
    }
}
