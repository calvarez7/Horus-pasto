<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class CtTemp extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'id','code','date','fileName','numberRecords','paq_temps_id',
    ];
}
