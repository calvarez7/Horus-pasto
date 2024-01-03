<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class representante_legal extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'representante_legals';
    protected $guarded = [];
}
