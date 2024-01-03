<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Patologia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'CitaPaciente_id', 'Patologiacancelactual', 'fdxcanceractual', 'flaboratoriopatologia', 'tumorsegunbiopsia',
        'localizacioncancer', 'Dukes', 'gleason', 'Her2', 'Estadificaciónclinica', 'estadificacioninicial',
        'fechaestadificacion',
    ];

    public function citapaciente()
    {
        return $this->hasMany('App\Modelos\citapaciente');
    }

}
