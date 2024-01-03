<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class TipoAgenda extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['name', 'Estado_id', 'modalidad', 'sms'];

    public function especialidades()
    {
        return $this->hasMany('App\Modelos\Especialidade');
    }
}
