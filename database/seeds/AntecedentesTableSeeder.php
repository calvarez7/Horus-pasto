<?php

use Illuminate\Database\Seeder;
use App\Modelos\Antecedente;

class AntecedentesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Antecedente::create(['Nombre' => 'Hipertension Arterial', 'Esfamiliar' => '1']);
        Antecedente::create(['Nombre' => 'Dislipidemia', 'Esfamiliar' => '1']);
        Antecedente::create(['Nombre' => 'EPOC', 'Esfamiliar' => '1']);
        Antecedente::create(['Nombre' => 'Hipotiroidismo', 'Esfamiliar' => '1']);
        Antecedente::create(['Nombre' => 'Maltrato Familiar', 'Esfamiliar' => '1']);
        Antecedente::create(['Nombre' => 'Otros', 'Esfamiliar' => '1']);
        Antecedente::create(['Nombre' => 'Violencia Sexual', 'Esfamiliar' => '1']);
        Antecedente::create(['Nombre' => 'Diabetes Mellitus', 'Esfamiliar' => '1']);
        Antecedente::create(['Nombre' => 'Cáncer', 'Esfamiliar' => '1']);
        Antecedente::create(['Nombre' => 'Cardiopatía Isquémica', 'Esfamiliar' => '1']);
        Antecedente::create(['Nombre' => 'Enfermedad Tiroidea']);
        Antecedente::create(['Nombre' => 'Quirúrgicos']);
        Antecedente::create(['Nombre' => 'Alergias a Medicamentos']);
        Antecedente::create(['Nombre' => 'Transfusiones']);
        Antecedente::create(['Nombre' => 'Hipotiroidismo Congénito']);
        Antecedente::create(['Nombre' => 'Enfermedad Acido-Péptica']);
        Antecedente::create(['Nombre' => 'Enfermedad Cardiovascular']);
        Antecedente::create(['Nombre' => 'Transtorno Psiquiatrico']);
        Antecedente::create(['Nombre' => 'Traumas']);
        Antecedente::create(['Nombre' => 'Alergias a Medicamentos']);
        Antecedente::create(['Nombre' => 'Farmacológicos']);
        Antecedente::create(['Nombre' => 'Víctima Maltrato']);
        Antecedente::create(['Nombre' => 'Hipertensión Inducida por Embarazo']);
        Antecedente::create(['Nombre' => 'Cáncer Mama']);
        Antecedente::create(['Nombre' => 'Sintomático Respiratorio']);
        Antecedente::create(['Nombre' => 'TB Multidrogorresistente']);
        Antecedente::create(['Nombre' => 'Lepra']);
        Antecedente::create(['Nombre' => 'Infecciosos']);
        Antecedente::create(['Nombre' => 'Obesidad']);
        Antecedente::create(['Nombre' => 'Desnutrición']);
        Antecedente::create(['Nombre' => 'ITS']);
        Antecedente::create(['Nombre' => 'Discapacidad']);
        Antecedente::create(['Nombre' => 'Última Citología']);
    }
}
