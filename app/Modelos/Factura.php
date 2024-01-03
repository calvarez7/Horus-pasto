<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'usuario_id',
        'paciente_id',
        'tipofactura_id',
        'estado_id',
        'valor_domicilio',
        'valor_total',
        'valor_empaque',
        'cantidad_empaques',
        'fecha_cobro',
        'observacion_final',
        'forma_pago',
        'total_efectivo',
        'fecha_corte'
    ];

    public function detallecompra()
    {
        return $this->hasMany('App\Modelos\Detallecompra');
    }
}
