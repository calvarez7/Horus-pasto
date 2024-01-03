<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ahripsfias extends Model 
{
    protected $guarded = [];
    protected $table = 'AH_RIPS_FIAS';

    public function scopeWhereFecha($query, $mes, $anio)
    {
        return $query->whereMonth(DB::raw("CONVERT(datetime, AH_RIPS_FIAS.Fecha_Egreso, 103)"), $mes)
            ->whereYear(DB::raw("CONVERT(datetime, AH_RIPS_FIAS.Fecha_Egreso, 103)"), $anio);
    }

    public function scopeWhereDepartamento($query, $departamento_id){
        if($departamento_id){
            $query->where('AH_RIPS_FIAS.dane_dpto', $departamento_id);
        }
    }

    public function scopeWhereAmbito($query, $ambito){
        return $query->whereIn('AH_RIPS_FIAS.cod_cup', $ambito);
    }
}
