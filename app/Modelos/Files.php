<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Name', 'Path', 'CitaPaciente_id',
    ];

    public function citapaciente()
    {
        return $this->belongsTo('App\Modelos\citapaciente');
    }
}
