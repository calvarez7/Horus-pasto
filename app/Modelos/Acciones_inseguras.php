<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Acciones_inseguras extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [ 'name','condiciones_paciente','metodos','colaborador',
    'equipo_trabajo','ambiente','recursos','contexto', 'analisisevento_id'
    ];

    protected $casts = [
        'condiciones_paciente' => 'array',
        'metodos' => 'array',
        'colaborador' => 'array',
        'equipo_trabajo' => 'array',
        'ambiente' => 'array',
        'recursos' => 'array',
        'contexto' => 'array',
        'created_at' => "datetime:Y-m-d H:i:s",
        'updated_at' => "datetime:Y-m-d H:i:s",
    ];
}
