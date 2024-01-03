<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class AuditoriaNovedades extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'paciente_id', 'Tipo_id', 'fecha_novedad', 'fecha_creacion','movtivo', 'userActualiza_id',
        'estado_id','novedad_id'];
}
