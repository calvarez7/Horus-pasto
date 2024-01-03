<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\SedeproveedoreRepository;

class Sedeproveedore extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'Municipio_id', 'Prestador_id', 'Codigo_habilitacion', 'Nombre', 'Nivelatencion', 'Correo1', 'Correo2', 'Telefono1', 'Telefono2', 'Direccion', 'Codigo', 'Estado_id', 'fias'
    ];

    public function prestador()
    {
        return $this->belongsTo('App\Modelos\Prestadore');
    }

    public function municipio()
    {
        return $this->belongsTo('App\Modelos\Municipio');
    }

    public function contratos()
    {
        return $this->hasMany('App\Modelos\Contratos');
    }

    public function codepropios()
    {
        return $this->belongsToMany('App\Modelos\Codepropio', 'code_propio_sede_prestador', 'sedeproveedor_id', 'codepropio_id')->withPivot('Ambito', 'Valor')->withTimestamps();
    }

    public static function getRepository(){
        return  new SedeproveedoreRepository();
    }

    public function scopeWhereProveedor($query, $proveedor_id){
        if($proveedor_id){
            return $query->where('Prestador_id', $proveedor_id)
                ->whereNotNull('Codigo');
        }
    }
}
