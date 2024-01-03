<?php

namespace App\Repositories;

use App\Modelos\Sedeproveedore;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CTCovidRepository{

    /**
     * Ejecuta el SP para generar los rips
     * @param Object $data
     * @return Collection
     * @author David Peláez
     */
    public function consultar($data) {

        $consulta = collect(DB::select("SET NOCOUNT ON exec dbo.SP_RIPS_COVID_CT ?,?,?,?", [$data->fecha_inicial, $data->fecha_final, $data->entidad, $data->sede]));
        return $consulta->map(function ($item){
            return [
                "cod_habilitacion_sede" => $item->cod_habilitacion_sede,
                "fecha_remision" => $item->fecha_remision,
                "codigo_archivo" => $item->codigo_archivo,
                "total_registr" => $item->total_registr
            ];
        });

        /*
        $ObjAc = new ACRepository();
        $ObjAm = new AMRepository();
        $ObjAp = new APRepository();
        $ObjAt = new ATRepository();

        $sedes = Sedeproveedore::WhereProveedor(67)
            ->whereNotNull('Codigo')
            ->get('Codigo');

        $array = array();    
        $fechaCodigo = Carbon::parse($data->fecha_final)->format('Ym');
        $fecha = Carbon::parse($data->fecha_final)->format('d/m/Y');

        foreach ($sedes as $sede){

            $objeto = (Object)[
                'fecha_inicial' => $data->fecha_inicial,
                'fecha_final' => $data->fecha_final,
                'entidad' => $data->entidad,
                'sede' => $sede->Codigo,
            ];

            $ACRips = $ObjAc->consultar($objeto);
            $AMRips = $ObjAm->consultar($objeto);
            $APRips = $ObjAp->consultar($objeto);
            $ATRips = $ObjAt->consultar($objeto);

            
            if(count($ACRips)){
                array_push($array, (object)[
                    'codigo_prestador' => $sede->Codigo,
                    'fecha_remision' => $fecha,
                    'codigo_archivo' => 'AC'. $fechaCodigo,
                    'total' => count($ACRips)
                ]);
            }
            if(count($AMRips)){
                array_push($array, (object)[
                    'codigo_prestador' => $sede->Codigo,
                    'fecha_remision' => $fecha,
                    'codigo_archivo' => 'AM'. $fechaCodigo,
                    'total' => count($AMRips)
                ]);
            }
            if(count($APRips)){
                array_push($array, (object)[
                    'codigo_prestador' => $sede->Codigo,
                    'fecha_remision' => $fecha,
                    'codigo_archivo' => 'AP'. $fechaCodigo,
                    'total' => count($APRips)
                ]);
            }
            if(count($ATRips)){
                array_push($array, (object)[
                    'codigo_prestador' => $sede->Codigo,
                    'fecha_remision' => $fecha,
                    'codigo_archivo' => 'AT'. $fechaCodigo,
                    'total' => count($ATRips)
                ]);
            }
            
        }

        return $array;
        */
    }

}   