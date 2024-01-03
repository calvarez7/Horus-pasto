<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Users_plantillaimagenologia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'user_id', 'plantilla_id', 'estado_id'
    ];
}
