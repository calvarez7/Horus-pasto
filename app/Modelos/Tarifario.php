<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tarifario extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Cup_id', 'Sedeproveedor_id', 'Fechainicio', 'Fechafinal', 'Tarifa', 'Valor', 'Manual', 'Ambito', 'Estado_id', 'Contrato_id',
    ];

    public function cupservicio()
    {
        return $this->belongsTo('App\Modelos\Cupservicio');
    }

    public function contrato()
    {
        return $this->belongsTo('App\Modelos\Contrato');
    }
}
