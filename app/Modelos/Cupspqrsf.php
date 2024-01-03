<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Cupspqrsf extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Cup_id','Pqrsf_id'];

    protected $table = 'cupspqrsf';
}
