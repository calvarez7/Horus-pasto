<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Cupfamilia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Cup_id', 'Familia_id',
    ];

    public function cup()
    {
        return $this->belongsTo('App\Modelos\Cup');
    }

    public function familia()
    {
        return $this->belongsTo('App\Modelos\Familia');
    }
}
