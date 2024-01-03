<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntecedenteToxicologicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedente_toxicologicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('checkboxMedicamentos')->nullable();
            $table->text('medicamento')->nullable();
            $table->boolean('checkboxAlimentarios')->nullable();
            $table->text('Alimentarios')->nullable();
            $table->boolean('checkboxSPA')->nullable();
            $table->text('SPA')->nullable();
            $table->boolean('checkboxAlcohol')->nullable();
            $table->text('Alcohol')->nullable();
            $table->boolean('checkboxOtrasSustacias')->nullable();
            $table->text('OtrasSustacias')->nullable();
            $table->bigInteger('Citapaciente_id')->nullable()->unsigned()->index();
            $table->foreign('Citapaciente_id')->references('id')->on('cita_paciente');
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
        Schema::dropIfExists('antecedente_toxicologicos');
    }
}
