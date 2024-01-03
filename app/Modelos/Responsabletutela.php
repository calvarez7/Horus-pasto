<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Responsabletutela extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Rol_id', 'Estado_id',
    ];

}
