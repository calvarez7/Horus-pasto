<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ACRepository{

    /**
     * Ejecuta el SP para generar los rips
     * @param Object $data
     * @author David Peláez
     */
    public function consultar($data) {
        $consulta = collect(DB::select("SET NOCOUNT ON exec dbo.SP_RIPS_AC ?,?,?,?", [$data->fecha_inicial, $data->fecha_final, $data->entidad, $data->sede]));
        return $consulta->map(function ($item){
            return [
                'nro_factura' => $item->nro_factura,
                'cod_habilitacion_sede' => $item->cod_habilitacion_sede,
                'tipo_doc' => $item->tipo_doc,
                'num_doc' => $item->num_doc,
                'fecha_consulta' => $item->fecha_consulta,
                'orden_id' => $item->orden_id,
                'cod_cup' => $item->cod_cup,
                'finalidad' => $item->finalidad,
                'causa_externa' => $item->causa_externa,
                'dx_principal' => $item->dx_principal,
                'dx_relacionado1' => $item->dx_relacionado1,
                'dx_relacionado2' => $item->dx_relacionado2,
                'dx_relacionado3' => $item->dx_relacionado3,
                'tipo_diagnostico' => $item->tipo_diagnostico,
                'valor_consulta' => round($item->valor_consulta, 3),
                'valor_cuota_moderadora' => round($item->valor_cuota_moderadora, 3),
                'valor_neto_pagar' => round($item->valor_neto_pagar, 3),
            ];
        });
    }

}