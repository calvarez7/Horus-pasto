<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class actividaduser extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'User_id', 'Actividadcargo_id',
    ];

    public function actividadcargo()
    {
        return $this->belongsTo('App\Modelos\Actividadcargo');
    }
}
