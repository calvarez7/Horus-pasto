<?php

namespace App\Modelos;

use App\Repositories\AreaRepository;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Nombre','Descripcion'
    ];

    public static function getRepository(){
        return  new AreaRepository();
    }
}
