<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tiporequerimiento extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Nombre', 'Dias', 'User_id',
    ];
}
