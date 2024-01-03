<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Parentesco extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Nombre',
    ];

    public function parentescoantecedentes()
    {
        return $this->hasMany('App\Modelos\Parentescoantecedente');
    }
}
