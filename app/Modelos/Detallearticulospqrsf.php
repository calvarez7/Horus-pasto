<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Detallearticulospqrsf extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Detallearticulo_id','Pqrsf_id'];

    protected $table = 'detallearticulospqrsf';
}
