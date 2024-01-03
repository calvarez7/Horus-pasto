<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class ciclo_menu_productos extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'ciclo_menu_productos';

    protected $guarded = [];
}
