<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Adjuntos_tutelas extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Tutela_id', 'Ruta', 'Nombre',
    ];

    protected $table = 'adjuntos_tutelas';

    public function tutelas()
    {
        return $this->hasMany('App\Modelos\Tutelas');
    }
}
