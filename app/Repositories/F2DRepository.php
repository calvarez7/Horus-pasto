<?php

namespace App\Repositories;

use App\Interfaces\FiasInterface;
use App\Modelos\Ac;
use App\Modelos\Acripsfias;
use Illuminate\Support\Facades\DB;

class F2DRepository implements FiasInterface{

    protected $cup_ambulatorios = [
        '890101',
        '890102',
        '890103',
        '890105',
        '890106',
        '890108',
        '890109',
        '890110',
        '890111',
        '890112',
        '890113',
        '890114',
        '890115',
        '890116'
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