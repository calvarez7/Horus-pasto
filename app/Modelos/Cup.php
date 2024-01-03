<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Cup extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'Codigo', 'Nombre', 'Genero', 'Edad_Inicial', 'Edad_Final', 'Archivo', 'Qx', 'Dx_Requerido', 'Nivelordenamiento', 'Requiere_auditoria',
        'Peridiocidad', 'Ufuncional', 'Servicio_id', 'Cantidadmaxordenar','estancia',
    ];

    protected $appends =['codigo_nombre'];

    public function getCodigoNombreAttribute(){
         return "{$this->Codigo} - {$this->Nombre}";
    }

    public function cupservicios()
    {
        return $this->hasMany('App\Modelos\Cupservicio');
    }

    public function cup()
    {
        return $this->belongsTo('App\Modelos\Cup');
    }

    public function codigomanual()
    {
        return $this->hasMany('App\Modelos\Codigomanual');
    }

    public function ordens()
    {
        return $this->hasMany('App\Modelos\Orden');
    }
}
