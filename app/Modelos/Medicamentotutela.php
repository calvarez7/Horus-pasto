<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Medicamentotutela extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Tutela_id', 'Detallearticulo_id',
    ];
}
