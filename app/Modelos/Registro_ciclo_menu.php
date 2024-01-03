<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Registro_ciclo_menu extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $table = 'registro_ciclo_menu';

    protected $guarded = [];

    public function ciclos()
    {
        return $this->belongsTo(ciclos_menus::class);
    }
}
