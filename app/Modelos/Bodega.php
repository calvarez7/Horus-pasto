<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'Municipio_id', 'Tipobodega_id', 'Nombre', 'Direccion', 'Telefono', 'Horainicio', 'Horafin', 'Estado', 'stock_seguridad', 'tiempo_reposicion', 'cobertura'
    ];

    public function tipobodega()
    {
        return $this->belongsTo('App\Modelos\Tipobodega');
    }
}
