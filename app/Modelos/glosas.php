<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class glosas extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'estado_id', 'User_id', 'ac_id', 'us_id', 'ap_id', 'au_id', 'ah_id',
        'an_id', 'am_id', 'descripcion', 'valor', 'archivo', 'at_id', 'codigo',
        'concepto', 'af_id'
    ];
}
