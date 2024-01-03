<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewcamposToAntecedenteBiopsicosocials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('antecedente_biopsicosocials', function (Blueprint $table) {
            $table->string('violencia')->nullable();
            $table->boolean('checkboxSignosViolencia')->nullable();
            $table->boolean('checkboxPresenciaMutilacion')->nullable();
            $table->string('PresenciaMutilacion')->nullable();
            $table->boolean('checkboxMatrimonioInfantil')->nullable();
            $table->string('MatrimonioInfantil')->nullable();
            $table->string('identidad_generoTransgenero')->nullable();
            $table->string('Espermarquia')->nullable();
            $table->string('Menarquia')->nullable();
            $table->boolean('checkboxSufridoIts')->nullable();
            $table->string('CualIts')->nullable();
            $table->string('TratamientoIts')->nullable();
            $table->string('fecha_enfermedadIts')->nullable();
            $table->string('Descripciondificultad')->nullable();        
            $table->boolean('checkboxCicloMenstrual')->nullable();
            $table->string('Ciclos')->nullable();
            $table->string('trabaja')->nullable();
            $table->string('iglesia')->nullable();
            $table->string('ClubDeportivo')->nullable();
            $table->string('Amigos')->nullable();
            $table->string('Asiste_colegio')->nullable();
            $table->string('Comparte_Vecinos')->nullable();
            $table->string('Club_Social')->nullable();
            $table->string('Cual_club')->nullable();
            $table->string('Observacion_club')->nullable();
            $table->string('Ayuda_Familia')->nullable();
            $table->string('Habla_Familia')->nullable();
            $table->string('Cosas_nuevas')->nullable();
            $table->string('Familia_Cuando')->nullable();
            $table->string('Familia_Tiempo')->nullable();
            $table->string('CiclosMnestruales')->nullable();
            $table->string('Resultado')->nullable();
            $table->string('Vinculos')->nullable();
            $table->string('Relacion')->nullable();
            $table->string('problemasSalud')->nullable();
            $table->string('cualsalud')->nullable();
            $table->string('Observacion_Salud')->nullable();
            $table->string('TipoFamilia')->nullable();
            $table->string('cuantas_familia')->nullable();
            $table->string('hijos_conforman')->nullable();
            $table->string('actividad_laboral')->nullable();
            $table->string('alteraciones')->nullable();
            $table->string('historia_repro')->nullable();
            $table->string('Paridad')->nullable();
            $table->string('cesarea')->nullable();
            $table->string('preeclampsia')->nullable();
            $table->string('Abortos_Recurrentes')->nullable();
            $table->string('Hemorragia_Pos')->nullable();
            $table->string('Peso_recien')->nullable();
            $table->string('Mortalidad_fetal')->nullable();
            $table->string('Trabajo_parto')->nullable();
            $table->string('Cirugia_Gineco')->nullable();
            $table->string('Renal')->nullable();
            $table->string('Diabetes_Gestacional')->nullable();
            $table->string('Hemorragia')->nullable();
            $table->string('semanas_hemorragia')->nullable();
            $table->string('Anemia')->nullable();
            $table->string('valor_anemia')->nullable();
            $table->string('Embarazo_prolongado')->nullable();
            $table->string('semanas_prolongado')->nullable();
            $table->string( 'Hiper_arterial')->nullable();
            $table->string('Polihidramnios')->nullable();
            $table->string('Embarazo_multiple')->nullable();
            $table->string('Presente_frente')->nullable();
            $table->string('Isoinmunización')->nullable();
            $table->string('Ansiedad_severa')->nullable();
            $table->string('familiar_inadecuado')->nullable();
            $table->string('Aviso')->nullable();
            $table->string('Resultadopre')->nullable();
            $table->string('tipodecisionesSexRep')->nullable();
            $table->string('sufrido_its')->nullable();
            $table->boolean('checkboxDecisionesSexRep')->nullable();
            $table->string('decisionesSexRep')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('antecedente_biopsicosocials', function (Blueprint $table) {
            $table->string('violencia')->nullable();
            $table->boolean('checkboxSignosViolencia')->nullable();
            $table->boolean('checkboxPresenciaMutilacion')->nullable();
            $table->string('PresenciaMutilacion')->nullable();
            $table->boolean('checkboxMatrimonioInfantil')->nullable();
            $table->string('MatrimonioInfantil')->nullable();
            $table->string('identidad_generoTransgenero')->nullable();
            $table->string('Espermarquia')->nullable();
            $table->string('Menarquia')->nullable();
            $table->boolean('checkboxSufridoIts')->nullable();
            $table->string('CualIts')->nullable();
            $table->string('TratamientoIts')->nullable();
            $table->string('fecha_enfermedadIts')->nullable();
            $table->string('Descripciondificultad')->nullable();        
            $table->boolean('checkboxCicloMenstrual')->nullable();
            $table->string('Ciclos')->nullable();
            $table->string('trabaja')->nullable();
            $table->string('iglesia')->nullable();
            $table->string('ClubDeportivo')->nullable();
            $table->string('Amigos')->nullable();
            $table->string('Asiste_colegio')->nullable();
            $table->string('Comparte_Vecinos')->nullable();
            $table->string('Club_Social')->nullable();
            $table->string('Cual_club')->nullable();
            $table->string('Observacion_club')->nullable();
            $table->string('Ayuda_Familia')->nullable();
            $table->string('Habla_Familia')->nullable();
            $table->string('Cosas_nuevas')->nullable();
            $table->string('Familia_Cuando')->nullable();
            $table->string('Familia_Tiempo')->nullable();
            $table->string('CiclosMnestruales')->nullable();
            $table->string('Resultado')->nullable();
            $table->string('Vinculos')->nullable();
            $table->string('Relacion')->nullable();
            $table->string('problemasSalud')->nullable();
            $table->string('cualsalud')->nullable();
            $table->string('Observacion_Salud')->nullable();
            $table->string('TipoFamilia')->nullable();
            $table->string('cuantas_familia')->nullable();
            $table->string('hijos_conforman')->nullable();
            $table->string('actividad_laboral')->nullable();
            $table->string('alteraciones')->nullable();
            $table->string('historia_repro')->nullable();
            $table->string('Paridad')->nullable();
            $table->string('cesarea')->nullable();
            $table->string('preeclampsia')->nullable();
            $table->string('Abortos_Recurrentes')->nullable();
            $table->string('Hemorragia_Pos')->nullable();
            $table->string('Peso_recien')->nullable();
            $table->string('Mortalidad_fetal')->nullable();
            $table->string('Trabajo_parto')->nullable();
            $table->string('Cirugia_Gineco')->nullable();
            $table->string('Renal')->nullable();
            $table->string('Diabetes_Gestacional')->nullable();
            $table->string('Hemorragia')->nullable();
            $table->string('semanas_hemorragia')->nullable();
            $table->string('Anemia')->nullable();
            $table->string('valor_anemia')->nullable();
            $table->string('Embarazo_prolongado')->nullable();
            $table->string('semanas_prolongado')->nullable();
            $table->string( 'Hiper_arterial')->nullable();
            $table->string('Polihidramnios')->nullable();
            $table->string('Embarazo_multiple')->nullable();
            $table->string('Presente_frente')->nullable();
            $table->string('Isoinmunización')->nullable();
            $table->string('Ansiedad_severa')->nullable();
            $table->string('familiar_inadecuado')->nullable();
            $table->string('Aviso')->nullable();
            $table->string('Resultadopre')->nullable();
            $table->string('tipodecisionesSexRep')->nullable();
            $table->string('sufrido_its')->nullable();
            $table->boolean('checkboxDecisionesSexRep')->nullable();
            $table->string('decisionesSexRep')->nullable();
        });
    }
}
