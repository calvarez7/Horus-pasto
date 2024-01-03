<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class CupsordenCitapaciente extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['cupordens_id', 'citapaciente_id'];
}
