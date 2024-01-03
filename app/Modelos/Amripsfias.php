<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Amripsfias extends Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $guarded = [];
    protected $table = 'AM_RIPS_FIAS';

    public function scopeWhereFecha($query, $mes, $anio)
    {
        return $query->whereMonth(DB::raw("CONVERT(datetime, fecha_consulta, 103)"), $mes)
            ->whereYear(DB::raw("CONVERT(datetime, fecha_consulta, 103)"), $anio);
        
        //return $query->whereMonth("Fecha_Consulta", $mes)
        //    ->whereYear("Fecha_Consulta", $anio);
    }

    public function scopeWhereDepartamento($query, $departamento_id){
        if($departamento_id){
            $query->where('AM_RIPS_FIAS.dane_dpto', $departamento_id);
        }
    }

    public function scopeWhereAmbito($query, $ambito){
        return $query->whereIn('AM_RIPS_FIAS.cod_cup', $ambito);
    }
}
