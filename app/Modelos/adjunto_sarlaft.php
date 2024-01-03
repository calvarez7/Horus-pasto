<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class adjunto_sarlaft extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'adjunto_sarlafts';
    protected $fillable = [];
}
