<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsPackages202sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details_packages_202s', function (Blueprint $table) {
            $table->bigIncrements('id');
            for ($i = 0;$i <= 118;$i++){
                $table->string($i)->nullable();
            }
            $table->bigInteger('package_202_id')->nullable()->unsigned()->index();
            $table->foreign('package_202_id')->references('id')->on('packages_202s');
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
        Schema::dropIfExists('details_packages_202s');
    }
}
