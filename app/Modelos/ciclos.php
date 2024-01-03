<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class ciclos extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'ciclos';
    protected $fillable = [
        'id'
    ];

    public function menu()
    {
        return $this->belongsToMany(Menu::class);
    }
}
