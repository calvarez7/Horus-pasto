<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ATCovidRepository{

    /**
     * Ejecuta el SP para generar los rips
     * @param Object $data
     * @return Collection $consulta
     * @author David Peláez
     */
    public function consultar($data) {
        $consulta = collect(DB::select("SET NOCOUNT ON exec dbo.SP_RIPS_COVID_AT ?,?,?,?", [$data->fecha_inicial, $data->fecha_final, $data->entidad, $data->sede]));
        return $consulta->map(function ($item){
            return [
                'nro_factura' => $item->nro_factura,
                'cod_habilitacion_sede' => $item->cod_habilitacion_sede,
                'tipo_doc' => $item->tipo_doc,
                'num_doc' => $item->num_doc,
                'nro_autorizacion' => $item->nro_autorizacion,
                'tipo_servicio' => $item->tipo_servicio,
                'codigo_servicio' => $item->codigo_servicio,
                'nombre_servicio' => $item->nombre_servicio,
                'cantidad' => $item->cantidad,
                'valor_unitario' => round($item->valor_unitario, 3),
                'valor_total' => round($item->valor_total, 3),
            ];
        });
    }

}  