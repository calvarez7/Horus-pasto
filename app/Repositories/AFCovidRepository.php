<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AFCovidRepository{

    /**
     * Ejecuta el AF  para generar los rips
     * @param Object $data
     * @author David Peláez
     */
    public function consultar($data) {
        $consulta = collect(DB::select("SET NOCOUNT ON exec dbo.SP_RIPS_COVID_AF ?,?,?,?", [$data->fecha_inicial, $data->fecha_final, $data->entidad, $data->sede]));
        return $consulta->map(function ($item){
            return [
                'cod_habilitacion_sede' => $item->cod_habilitacion_sede,
                'razon_social' => $item->razon_social,
                'tipo_identificacion' => $item->tipo_identificacion,
                'numero_identificacion' => $item->numero_identificacion,
                'nro_factura' => $item->nro_factura,
                'fecha_expedicion' => $item->fecha_expedicion,
                'fecha_inicial' => $item->fecha_inicial,
                'fecha_final' => $item->fecha_final,
                'codigo_entidad' => $item->codigo_entidad,
                'nombre_entidad' => $item->nombre_entidad,
                'numero_contrato' => $item->numero_contrato,
                'plan_beneficios' => $item->plan_beneficios,
                'nro_polizas' => ($item->nro_polizas),
                'valor_copago' => round($item->valor_copago, 3),
                'valor_comision' => round($item->valor_comision, 3),
                'valor_descuentos' => round($item->valor_descuentos, 3),
                'valor_neto_pagar' => round($item->valor_neto_pagar, 3),
            ];
        });
    }
    
} 