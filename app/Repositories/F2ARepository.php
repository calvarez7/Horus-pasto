<?php

namespace App\Repositories;

use App\Interfaces\FiasInterface;
use App\Modelos\Acripsfias;
use Illuminate\Support\Facades\DB;

class F2ARepository implements FiasInterface{

    protected $cup_ambulatorios = [
        '890201',
        '890202',
        '890203',
        '890204',
        '890205',
        '890206',
        '890207',
        '890208',
        '890209',
        '890210',
        '890211',
        '890212',
        '890213',
        '890214',
        '890215',
        '890216',
        '890217',
        '890218',
        '890219',
        '890220',
        '890221',
        '890222',
        '890223',
        '890224',
        '890225',
        '890226',
        '890227',
        '890228',
        '890229',
        '890230',
        '890231',
        '890232',
        '890233',
        '890234',
        '890235',
        '890236',
        '890237',
        '890238',
        '890239',
        '890240',
        '890241',
        '890242',
        '890243',
        '890244',
        '890245',
        '890246',
        '890247',
        '890248',
        '890249',
        '890250',
        '890251',
        '890252',
        '890253',
        '890254',
        '890255',
        '890256',
        '890257',
        '890258',
        '890259',
        '890260',
        '890261',
        '890262',
        '890263',
        '890264',
        '890265',
        '890266',
        '890267',
        '890268',
        '890269',
        '890270',
        '890271',
        '890272',
        '890273',
        '890274',
        '890275',
        '890276',
        '890277',
        '890278',
        '890279',
        '890280',
        '890281',
        '890282',
        '890283',
        '890284',
        '890285',
        '890286',
        '890287',
        '890288',
        '890289',
        '890290',
        '890291',
        '890292',
        '890294',
        '890295',
        '890297',
        '890301',
        '890302',
        '890303',
        '890304',
        '890305',
        '890306',
        '890307',
        '890308',
        '890309',
        '890310',
        '890311',
        '890312',
        '890313',
        '890314',
        '890315',
        '890316',
        '890317',
        '890318',
        '890319',
        '890320',
        '890321',
        '890322',
        '890323',
        '890324',
        '890325',
        '890326',
        '890327',
        '890328',
        '890329',
        '890330',
        '890331',
        '890332',
        '890333',
        '890334',
        '890335',
        '890336',
        '890337',
        '890338',
        '890339',
        '890340',
        '890341',
        '890342',
        '890343',
        '890344',
        '890345',
        '890346',
        '890347',
        '890348',
        '890349',
        '890350',
        '890351',
        '890352',
        '890353',
        '890354',
        '890355',
        '890356',
        '890357',
        '890358',
        '890359',
        '890360',
        '890361',
        '890362',
        '890363',
        '890364',
        '890365',
        '890366',
        '890367',
        '890368',
        '890369',
        '890370',
        '890371',
        '890372',
        '890373',
        '890374',
        '890375',
        '890376',
        '890377',
        '890378',
        '890379',
        '890380',
        '890381',
        '890382',
        '890383',
        '890384',
        '890385',
        '890386',
        '890387',
        '890388',
        '890389',
        '890390',
        '890391',
        '890392',
        '890394',
        '890395',
        '890397',
        '890501',
        '890502',
        '890503',
    ];

    /**
     * Genera la consulta para realizar el fias
     * @param $data
     * @return Array
     * @author David Peláez
     */
    public function consultar($data){

        $select = [
            'AC_RIPS_FIAS.dane_dpto as codigo_departamento',
            'AC_RIPS_FIAS.dane_mpio as codigo_municipio',
            'AC_RIPS_FIAS.dx_principal as codigo_cie10',
            DB::raw('count (dx_principal) total')
        ];

        $select_pacientes = [
            'AC_RIPS_FIAS.dane_dpto as codigo_departamento',
            'AC_RIPS_FIAS.dane_mpio as codigo_municipio',
            'AC_RIPS_FIAS.dx_principal as codigo_cie10',
            'AC_RIPS_FIAS.edad',
            'pacientes.Sexo as sexo',
        ];

        $consulta = Acripsfias::select($select)
            ->whereFecha($data['mes'], $data['anio'])
            ->whereDepartamento($data['departamento_id'])
            ->whereAmbito($this->cup_ambulatorios)
            ->where('AC_RIPS_FIAS.dx_principal', 'not like', 'z%')
            ->groupBy('AC_RIPS_FIAS.dane_dpto', 'AC_RIPS_FIAS.dane_mpio', 'AC_RIPS_FIAS.dx_principal')
            ->orderBy('total', 'desc')
            ->get();

        $array = array();

        $departamentos = $consulta->pluck('codigo_departamento')->unique();
        foreach($departamentos as $departamento){
            $municipios = $consulta->where('codigo_departamento', $departamento)
                ->pluck('codigo_municipio')->unique();

            foreach($municipios as $municipio){
                $cie10s = $consulta->where('codigo_departamento', $departamento)
                    ->where('codigo_municipio', $municipio)
                    ->pluck('codigo_cie10')->take(20);

                foreach ($cie10s as $cie10) {
                    $pacientes = Acripsfias::select($select_pacientes)
                        ->join('pacientes', 'AC_RIPS_FIAS.num_doc', '=', 'pacientes.Num_Doc')
                        ->whereFecha($data['mes'], $data['anio'])
                        ->where('AC_RIPS_FIAS.dane_dpto', $departamento)
                        ->where('AC_RIPS_FIAS.dane_mpio', $municipio)
                        ->where('AC_RIPS_FIAS.dx_principal', $cie10)
                        ->whereAmbito($this->cup_ambulatorios)
                        ->where('AC_RIPS_FIAS.dx_principal', 'not like', 'z%')
                        ->get();

                    /** Inicializo los campos para poder asignar */
                    $array[$departamento][$municipio][$cie10]["0-5"]["M"] = 0;
                    $array[$departamento][$municipio][$cie10]["0-5"]["F"] = 0;
                    $array[$departamento][$municipio][$cie10]["6-11"]["M"] = 0;
                    $array[$departamento][$municipio][$cie10]["6-11"]["F"] = 0;
                    $array[$departamento][$municipio][$cie10]["12-17"]["M"] = 0;
                    $array[$departamento][$municipio][$cie10]["12-17"]["F"] = 0;
                    $array[$departamento][$municipio][$cie10]["18-28"]["M"] = 0;
                    $array[$departamento][$municipio][$cie10]["18-28"]["F"] = 0;
                    $array[$departamento][$municipio][$cie10]["29-34"]["M"] = 0;
                    $array[$departamento][$municipio][$cie10]["29-34"]["F"] = 0;
                    $array[$departamento][$municipio][$cie10]["35-39"]["M"] = 0;
                    $array[$departamento][$municipio][$cie10]["35-39"]["F"] = 0;
                    $array[$departamento][$municipio][$cie10]["40-44"]["M"] = 0;
                    $array[$departamento][$municipio][$cie10]["40-44"]["F"] = 0;
                    $array[$departamento][$municipio][$cie10]["45-49"]["M"] = 0;
                    $array[$departamento][$municipio][$cie10]["45-49"]["F"] = 0;
                    $array[$departamento][$municipio][$cie10]["50-54"]["M"] = 0;
                    $array[$departamento][$municipio][$cie10]["50-54"]["F"] = 0;
                    $array[$departamento][$municipio][$cie10]["55-59"]["M"] = 0;
                    $array[$departamento][$municipio][$cie10]["55-59"]["F"] = 0;
                    $array[$departamento][$municipio][$cie10]["60-64"]["M"] = 0;
                    $array[$departamento][$municipio][$cie10]["60-64"]["F"] = 0;
                    $array[$departamento][$municipio][$cie10]["65-69"]["M"] = 0;
                    $array[$departamento][$municipio][$cie10]["65-69"]["F"] = 0;
                    $array[$departamento][$municipio][$cie10]["70"]["M"] = 0;
                    $array[$departamento][$municipio][$cie10]["70"]["F"] = 0;

                    foreach ($pacientes as $paciente){

                        $array[$departamento][$municipio][$cie10]["0-5"]["M"] += $paciente->edad >= 0 && $paciente->edad <= 5 && $paciente->sexo == 'M' ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["0-5"]["F"] += $paciente->edad >= 0 && $paciente->edad <= 5 && $paciente->sexo == "F" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["6-11"]["M"] += $paciente->edad >= 6 && $paciente->edad <= 11 && $paciente->sexo == "M" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["6-11"]["F"] += $paciente->edad >= 6 && $paciente->edad <= 11 && $paciente->sexo == "F" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["12-17"]["M"] += $paciente->edad >= 12 && $paciente->edad <= 17 && $paciente->sexo == "M" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["12-17"]["F"] += $paciente->edad >= 12 && $paciente->edad <= 17 && $paciente->sexo == "F" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["18-28"]["M"] += $paciente->edad >= 18 && $paciente->edad <= 28 && $paciente->sexo == "M" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["18-28"]["F"] += $paciente->edad >= 18 && $paciente->edad <= 28 && $paciente->sexo == "F" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["29-34"]["M"] += $paciente->edad >= 29 && $paciente->edad <= 34 && $paciente->sexo == "M" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["29-34"]["F"] += $paciente->edad >= 29 && $paciente->edad <= 34 && $paciente->sexo == "F" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["35-39"]["M"] += $paciente->edad >= 35 && $paciente->edad <= 39 && $paciente->sexo == "M" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["35-39"]["F"] += $paciente->edad >= 35 && $paciente->edad <= 39 && $paciente->sexo == "F" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["40-44"]["M"] += $paciente->edad >= 40 && $paciente->edad <= 44 && $paciente->sexo == "M" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["40-44"]["F"] += $paciente->edad >= 40 && $paciente->edad <= 44 && $paciente->sexo == "F" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["45-49"]["M"] += $paciente->edad >= 45 && $paciente->edad <= 49 && $paciente->sexo == "M" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["45-49"]["F"] += $paciente->edad >= 45 && $paciente->edad <= 49 && $paciente->sexo == "F" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["50-54"]["M"] += $paciente->edad >= 50 && $paciente->edad <= 54 && $paciente->sexo == "M" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["50-54"]["F"] += $paciente->edad >= 50 && $paciente->edad <= 54 && $paciente->sexo == "F" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["55-59"]["M"] += $paciente->edad >= 55 && $paciente->edad <= 59 && $paciente->sexo == "M" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["55-59"]["F"] += $paciente->edad >= 55 && $paciente->edad <= 59 && $paciente->sexo == "F" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["60-64"]["M"] += $paciente->edad >= 60 && $paciente->edad <= 64 && $paciente->sexo == "M" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["60-64"]["F"] += $paciente->edad >= 60 && $paciente->edad <= 64 && $paciente->sexo == "F" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["65-69"]["M"] += $paciente->edad >= 65 && $paciente->edad <= 69 && $paciente->sexo == "M" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["65-69"]["F"] += $paciente->edad >= 65 && $paciente->edad <= 69 && $paciente->sexo == "F" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["70"]["M"] += $paciente->edad >= 70 && $paciente->sexo == "M" ? 1 : 0;
                        $array[$departamento][$municipio][$cie10]["70"]["F"] += $paciente->edad >= 70 && $paciente->sexo == "F" ? 1 : 0;
                    }
                    $array[$departamento][$municipio][$cie10]["total"] = $pacientes->count();
                }
            }
        }
        
        return $array;
        
    }

}