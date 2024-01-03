<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Gestions_pqrsf extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Pqrsf_id', 'User_id', 'Paciente_id', 'Tipo_id', 'Motivo', 'Fecha', 'Medio',
        'Aquien_not', 'Parentesco', 'Responsable'
    ];

    protected $table = 'gestions_pqrsf';

    public function Adjuntos_pqrsf()
    {
        return $this->hasMany('App\Modelos\Adjunto','Gestion_id');
    }
}
