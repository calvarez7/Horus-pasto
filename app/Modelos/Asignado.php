<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Asignado extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Permission_id','Pqrsf_id', 'Estado_id'];

    protected $table = 'asignados';
}
