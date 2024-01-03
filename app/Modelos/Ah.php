<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ah extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    //
    protected $guarded = [];

    public function scopeWhereDepartamento($query, $departamento_id){
        if($departamento_id){
            return $query->where('departamentos.codigo_Dane', $departamento_id);
        }else{
            return $query->whereIn('departamentos.codigo_Dane', ['05', '27']);
        }
    }

    public function scopeWhereFecha($query, $mes, $anio)
    {
        return $query->whereMonth(DB::raw("CONVERT(datetime, ahs.Fecha_Egreso, 103)"), $mes)
            ->whereYear(DB::raw("CONVERT(datetime, ahs.Fecha_Egreso, 103)"), $anio);
    }

    public function scopeConfirmado($query){
        /** El estado 7 es el confirmado */
        return $query->join('afs as a', 'ahs.Af_id','=','a.id')
            ->join('paq_rips','a.paq_id','=','paq_rips.id')
            ->where('paq_rips.estado_id', 7);
    }
}
