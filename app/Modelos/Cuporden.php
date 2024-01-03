<?php

namespace App\Modelos;

use App\Repositories\CupOrdenRepository;
use Illuminate\Database\Eloquent\Model;

class Cuporden extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $guarded = [];

    public function cup()
    {
        return $this->belongsTo('App\Modelos\Cup');
    }

    public function orden()
    {
        return $this->belongsTo('App\Modelos\Orden');
    }


    public static function getRepository()
    {
        return new CupOrdenRepository();
    }
}
