<?php

namespace App\Modelos;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RegistroLaboratorios extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $guarded = [];

    // protected $casts = [
    //     'fecha_validacion'=> 'datetime:Y-m-d',
    // ];

    // public function getAttributeFechaValidacion($key)
    // {
    //     return Carbon::parse($key)->format('YY-mm-dd');
    // }
}
