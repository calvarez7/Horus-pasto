<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Conteoinventario extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Lote_id', 'Conteo1', 'Conteo2', 'Value1', 'Conteo3', 'Conteo4', 'UserCrea_id', 'inventario_bodega_id', 'Estado_id','saldo_inicial'
    ];

    public function lote()
    {
        return $this->belongsTo('App\Modelos\Lote', 'Lote_id');
    }
}
