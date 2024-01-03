<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class BiomicroscopiaOftalmoscopia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
            'biomicroscopiaOd',
            'biomicroscopiaOi',
            'pioOd',
            'pioOi',
            'oftalmoscopiaOd',
            'oftalmoscopiaOi',
            'citapaciente_id',
];

public function citapaciente()
{
    return $this->belongsTo('App\Modelos\citapaciente');
}
}
