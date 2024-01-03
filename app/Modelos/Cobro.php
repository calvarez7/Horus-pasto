<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'paciente_id', 'usuario_id', 'orden_id', 'estado_id', 'valor', 'tipo_cobro','numero_referencia_pago','fecha_cobro','valor_cita'
    ];

    public function orden()
    {
        return $this->belongsTo('App\Modelos\Orden');
    }
}
