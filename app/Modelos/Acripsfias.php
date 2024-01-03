<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Acripsfias extends Model{

    protected $guarded = [];
    protected $table = 'AC_RIPS_FIAS';
    

    public function scopeWhereFecha($query, $mes, $anio)
    {
        return $query->whereMonth(DB::raw("CONVERT(datetime, fecha_consulta, 103)"), $mes)
            ->whereYear(DB::raw("CONVERT(datetime, fecha_consulta, 103)"), $anio);
    }

    public function scopeWhereDepartamento($query, $departamento_id){
        if($departamento_id){
            $query->where('AC_RIPS_FIAS.dane_dpto', $departamento_id);
        }
    }

    public function scopeWhereAmbito($query, $ambito){
        return $query->whereIn('AC_RIPS_FIAS.cod_cup', $ambito);
    }
}
