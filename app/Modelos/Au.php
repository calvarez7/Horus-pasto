<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Au extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    //
    protected $guarded = [];

    public function scopeConfirmado($query){
        /** El estado 7 es el confirmado */
        return $query->join('afs as a', 'aus.Af_id','=','a.id')
        ->join('paq_rips','a.paq_id','=','paq_rips.id')
        ->where('paq_rips.estado_id', 7);
    }

    public function scopeWhereDepartamento($query, $departamento_id){
        if($departamento_id){
            $query->where('departamentos.codigo_Dane', $departamento_id);
        }else{
            $query->whereIn('departamentos.codigo_Dane', ['05', '27']);
        }
    }

    public function scopeWhereFecha($query, $mes, $anio)
    {
        return $query->whereMonth(DB::raw("CONVERT(datetime, aus.Fechasalida_Usuario, 103)"), $mes)
            ->whereYear(DB::raw("CONVERT(datetime, aus.Fechasalida_Usuario, 103)"), $anio);
    }
}
