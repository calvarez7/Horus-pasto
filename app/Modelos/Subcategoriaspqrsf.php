<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Subcategoriaspqrsf extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = ['Subcategoria_id','Pqrsf_id'];

    protected $table = 'subcategoriaspqrsf';
}
