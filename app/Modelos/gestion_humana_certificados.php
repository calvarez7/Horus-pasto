<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class gestion_humana_certificados extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    //
}
