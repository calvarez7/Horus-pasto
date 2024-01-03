<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Autorizacion extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Articulorden_id', 'Cuporden_id', 'Usuario_id', 'Nota',
    ];
}
