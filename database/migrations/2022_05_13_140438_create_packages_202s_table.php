<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackages202sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages_202s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('eapba');
            $table->date('start_date');
            $table->date('final_date');
            $table->string('number_records');
            $table->boolean('partial')->nullable();
            $table->bigInteger('state_id')->unsigned()->index();
            $table->foreign('state_id')->references('id')->on('estados');
            $table->bigInteger('sedeproveedor_id')->unsigned()->index();
            $table->foreign('sedeproveedor_id')->references('id')->on('sedeproveedores');
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
        Schema::dropIfExists('packages_202s');
    }
}
