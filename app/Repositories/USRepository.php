<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class USRepository{

    /**
     * Ejecuta el US para generar los rips
     * @param Object $data
     * @author David Peláez
     */
    public function consultar($data) {

        if($data->sede === 0){
            $consulta = collect(DB::select("SET NOCOUNT ON exec dbo.SP_RIPS_US_CONGLOMERADO ?,?,?,?", [$data->fecha_inicial, $data->fecha_final, $data->entidad, 0]));
        }else{
            $consulta = collect(DB::select("SET NOCOUNT ON exec dbo.SP_RIPS_US ?,?,?,?", [$data->fecha_inicial, $data->fecha_final, $data->entidad, $data->sede]));
        }

        return $consulta->map(function($item){
            return [
                'tipo_doc' => $item->tipo_doc,
                'num_doc' => $item->num_doc,
                'codigo_entidad' => $item->codigo_entidad,
                'tipo_usuario' => $item->tipo_usuario,
                'Primer_Ape' => $item->Primer_Ape,
                'Segundo_Ape' => $item->Segundo_Ape,
                'Primer_Nom' => $item->Primer_Nom,
                'SegundoNom' => $item->SegundoNom,
                'edad' => $item->edad,
                'unidad_medida' => $item->unidad_medida,
                'Sexo' => $item->Sexo,
                'codigo_dpto' => $item->codigo_dpto,
                'codigo_mpio' => $item->codigo_mpio,
                'zona_residencia' => $item->zona_residencia,
            ];
        });
    }

}