<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Facturainicial extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['tipo', 'numero', 'fecha', 'cod_int', 'descripcion', 'presentacion', 'nom_com', 'cum', 'lote', 'f_venc',
        'laboratorio', 'embalaje', 'cajas', 'unidades', 'valor', 'total', 'nit', 'pedido', 'usuario'];
}
