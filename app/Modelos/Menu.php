<?php

namespace App\Modelos;

use App\modelos\ciclos;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $guarded = [];

    public function ciclo_menu()
    {
        return $this->belongsToMany(ciclos::class)->withTimestamps();
    }
}
