<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Areapqrsf extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Area_id','Pqrsf_id'];

    protected $table = 'areaspqrsf';
}
