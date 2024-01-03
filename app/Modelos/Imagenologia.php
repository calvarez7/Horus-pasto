<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Imagenologia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Citapaciente_id', 'Indicacion', 'Hallazgos', 'Conclusion', 'Notaclaratoria',
        'Cie10_id', 'Tecnica', 'medico_imagenologia', 'prioridad'
    ];

    public function cie10()
    {
        return $this->hasMany('App\Modelos\Cie10');
    }

}
