<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Empleadopqrsf extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Empleado_id','Pqrsf_id'];

    protected $table = 'empleadospqrsf';
}
