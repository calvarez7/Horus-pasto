<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class OportunidadCita extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'oportunidad_citas';

    public function scopeWhereFecha($query, $mes, $anio){
        return $query->whereMonth('fecha_asignacion', $mes)
            ->whereYear('fecha_asignacion', $anio);
    }

    public function scopeWhereDepartamento($query, $departamento_id){
        if($departamento_id){
            return $query->where("oportunidad_citas.dpto", $departamento_id);
        }else{
            return $query->whereIn("oportunidad_citas.dpto", ['05', '27']);
        }
    }

}
