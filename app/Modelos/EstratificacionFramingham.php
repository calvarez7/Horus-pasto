<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class EstratificacionFramingham extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'edad_puntaje','colesterol_total','colesterol_puntaje',
        'colesterol_hdl','colesterol_puntajehdl','fumador_puntaje','arterial_puntaje',
        'totales','tratamiento' , 'usuario_id','paciente_id', 'Citapaciente_id' ,
        'estado_id','diabetes_puntaje','porcentaje'
        ];
}
