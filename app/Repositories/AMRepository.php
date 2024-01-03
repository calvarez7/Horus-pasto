<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AMRepository{

    /**
     * Ejecuta el SP para generar los rips
     * @param Object $data
     * @author David Peláez
     */
    public function consultar($data) {
        $consulta =  collect(DB::select("SET NOCOUNT ON exec dbo.SP_RIPS_AM ?,?,?,?", [$data->fecha_inicial, $data->fecha_final, $data->entidad, $data->sede]));
        return $consulta->map(function ($item){
            return [
                'nro_factura' => $item->nro_factura, 
                'cod_habilitacion_sede' => $item->cod_habilitacion_sede, 
                'tipo_doc' => $item->tipo_doc, 
                'num_doc' => $item->num_doc, 
                'nro_autorizacion' => $item->nro_autorizacion, 
                'codigo_medicamento' => $item->codigo_medicamento, 
                'tipo_medicamento' => $item->tipo_medicamento, 
                'nombre_medicamento' => $item->nombre_medicamento, 
                'forma_farmaceutica' => $item->forma_farmaceutica, 
                'concentracion' => $item->concentracion, 
                'unidad_medida' => $item->unidad_medida, 
                'numero_unidades' => $item->numero_unidades, 
                'valor_unitario' => round($item->valor_unitario, 3), 
                'valor_total' => round($item->valor_total, 3),
            ];
        });
    }

}