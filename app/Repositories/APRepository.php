<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class APRepository{

    /**
     * Ejecuta el SP para generar los rips
     * @param Object $data
     * @author David Peláez
     */
    public function consultar($data) {
        $consulta = collect(DB::select("SET NOCOUNT ON exec dbo.SP_RIPS_AP ?,?,?,?", [$data->fecha_inicial, $data->fecha_final, $data->entidad, $data->sede]));
        return $consulta->map(function ($item){
            return [
                'nro_factura' => $item->nro_factura,
                'cod_habilitacion_sede' => $item->cod_habilitacion_sede,
                'tipo_doc' => $item->tipo_doc,
                'num_doc' => $item->num_doc,
                'fecha_procedimiento' => $item->fecha_procedimiento,
                'nro_autorizacion' => $item->nro_autorizacion,
                'codigo_procedimiento' => $item->codigo_procedimiento,
                'ambito_procedimiento' => $item->ambito_procedimiento,
                'finalidad_procedimiento' => $item->finalidad_procedimiento,
                'personal_atiende' => $item->personal_atiende,
                'diagnostico_principal' => $item->diagnostico_principal,
                'diagnostico_relacionado' => $item->diagnostico_relacionado,
                'complicacion' => $item->complicacion,
                'forma_realiza_acto_qx' => $item->forma_realiza_acto_qx,
                'valor_procedimiento' => round($item->valor_procedimiento, 3),
            ];
        });
    }

}