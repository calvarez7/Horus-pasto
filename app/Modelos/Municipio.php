<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function sede()
    {
        return $this->belongsTo('App\Modelos\Sede');
    }
}
