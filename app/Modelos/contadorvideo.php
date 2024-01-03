<?php

namespace App\Modelos;

use App\User;
use Illuminate\Database\Eloquent\Model;

class contadorvideo extends Model
{

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'contadorvideos';

    protected $fillable = ['visto','user_id','video_id'];
}
