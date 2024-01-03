<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class persona_expuesta extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'persona_expuestas';
    protected $guarded = [];
}
