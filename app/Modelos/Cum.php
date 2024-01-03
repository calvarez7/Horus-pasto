<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Cum extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    //
    protected $fillable = ['Expediente', 'Producto', 'Titular', 'Registro_Sanitario', 'Fecha_Expedicion', 'Fecha_Vencimiento', 'Estado_Registro', 'Expediente_Cum', 'Consecutivo', 'Cantidad_Cum', 'Descripción_Comercial', 'Estado_Cum', 'Fecha_Activo', 'Fecha_Inactivo', 'Muestra_Medica', 'Unidad', 'Atc', 'Descripcion_Atc', 'Via_Administracion', 'Concentracion', 'Principio_Activo', 'Unidad_Medida', 'Cantidad', 'Unidad_Referencia', 'Forma_Farmaceutica', 'Cum_Validacion'];
}
