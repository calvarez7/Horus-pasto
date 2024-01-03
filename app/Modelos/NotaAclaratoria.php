<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class NotaAclaratoria extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'nota_aclaratorias';
    protected $guarded = [];
}
