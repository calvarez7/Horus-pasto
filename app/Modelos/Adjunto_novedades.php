<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Adjunto_novedades extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'Adjunto_novedades';
    protected $guarded = [];
}
