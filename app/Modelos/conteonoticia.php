<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class conteonoticia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'visto',
        'user_id',
        'noticia_id'
    ];
}
