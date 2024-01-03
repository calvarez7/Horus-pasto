<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class IntentosCargue202 extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $table = 'intentos_cargue_202s';
    protected $guarded = [];
}
