<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class FormulaOptometria extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $guarded = [];

    public function orden()
    {
        return $this->belongsTo('App\Modelos\Orden');
    }
}

