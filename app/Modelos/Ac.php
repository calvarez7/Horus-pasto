<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ac extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $guarded = [];

    public function us(){
        return $this->belongsTo(Us::class, 'Us_id');
    }

    public function cup(){
        return $this->belongsTo(Cup::class, 'Codigo_Consulta');
    }

    public function scopeConfirmado($query){
        /** El estado 7 es el confirmado */
        return $query->where('acs.estado_id', 7);
    }

    public function scopeWhereFecha($query, $mes, $anio)
    {
        return $query->whereMonth(DB::raw("CONVERT(datetime, Fecha_Consulta, 103)"), $mes)
            ->whereYear(DB::raw("CONVERT(datetime, Fecha_Consulta, 103)"), $anio);
        
        //return $query->whereMonth("Fecha_Consulta", $mes)
        //    ->whereYear("Fecha_Consulta", $anio);
    }

    public function scopeWhereDepartamento($query, $departamento_id){
        if($departamento_id){
            $query->where('departamentos.codigo_Dane', $departamento_id);
        }else{
            $query->whereIn('departamentos.codigo_Dane', ['05', '27']);
        }
    }

    public function scopeWhereAmbito($query, $ambito){
        return $query->whereHas('cup', function(Builder $query) use ($ambito){
            $query->whereIn('Codigo', $ambito);
        });
    }
}
