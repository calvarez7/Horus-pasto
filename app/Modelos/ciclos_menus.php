<?php

namespace App\modelos;

use App\Modelos\Menu;
use Illuminate\Database\Eloquent\Model;

class ciclos_menus extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'ciclos_menus';
    protected $fillable = [
        'menu_id', 'ciclo_id'
    ];
}
