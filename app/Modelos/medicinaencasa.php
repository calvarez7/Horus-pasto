<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class medicinaencasa extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'remite','telIPSremite','emailmedico','nombrecuidador','celularcuidador',
        'responsablepaciente','celularresponsable','indicebarthel','presenciaconfusion',
        'estrategiacovi','voluntariamente','agua','luz','nevera','bano','internet','Estado_id',
        'citaPaciente_id', 'user_id'
    ];
}
