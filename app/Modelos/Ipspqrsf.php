<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Ipspqrsf extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['IPS_id','Pqrsf_id'];

    protected $table = 'ipspqrsf';
}
