<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Auripsfias extends Model
{

    protected $guarded = [];
    protected $table = 'AU_RIPS_FIAS';

    public function scopeWhereFecha($query, $mes, $anio)
    {
        return $query->whereMonth(DB::raw("CONVERT(datetime, AU_RIPS_FIAS.Fechasalida_Usuario, 103)"), $mes)
            ->whereYear(DB::raw("CONVERT(datetime, AU_RIPS_FIAS.Fechasalida_Usuario, 103)"), $anio);
    }

    public function scopeWhereDepartamento($query, $departamento_id){
        if($departamento_id){
            $query->where('AU_RIPS_FIAS.dane_dpto', $departamento_id);
        }
    }

    public function scopeWhereAmbito($query, $ambito){
        return $query->whereIn('AU_RIPS_FIAS.cod_cup', $ambito);
    }
}
