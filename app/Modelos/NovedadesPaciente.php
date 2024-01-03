<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class NovedadesPaciente extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'paciente_id', 'Tipo_id', 'fecha_novedad','fecha_creacion', 'movtivo', 'user_id',
        'estado_id','ipsorigen_portabilidad','telefonoorigen_portabilidad','correoorigen_portabilidad','fechaInicio_portabilidad',
        'fechaFinal_portabilidad','ipsdestino_portabilidad','departamentoReceptor','causa','otra_causa','municipio_receptor'
    ];
}
