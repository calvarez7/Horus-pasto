<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Cuptutela extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Tutela_id', 'Cup_id',
    ];

}
