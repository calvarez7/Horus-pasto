<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Detallecita extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Usuario_id', 'Citapaciente_id', 'Motivo', 'Estado_id', 'observacion',
        'cup_id', 'lado', 'tipo_paciente', 'prioridad', 'lectura', 'ubicacion',
        'tecnica', 'observa_admision', 'cama', 'aislado', 'observacion_aislado',
        'fecha_orden'
    ];

    public function Cup()
    {
        return $this->belongsTo('App\Modelos\Cup');
    }
}
