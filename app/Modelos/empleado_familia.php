<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class empleado_familia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'empleado_familia';

    protected $fillable = [
        'familiar_id',
        'empleado_id',
        'tipo_documento',
        'num_documento',
        'nombre',
        'fecha_nacimiento',
        'edad',
        'genero ',
        'escolaridad',
        'profesion',
        'depende_empleado',
        'vivecon_el_empleado',

    ];

    // public function familia()
    // {
    //     return $this->belongsTo(Empleado::class);
    // }
}
