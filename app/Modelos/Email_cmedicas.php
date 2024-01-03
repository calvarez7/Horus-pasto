<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Email_cmedicas extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['prestador_id', 'email'];
}
