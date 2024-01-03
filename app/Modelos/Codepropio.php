<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Codepropio extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Codigo', 'Descripcion', 'Codesumi_id', 'Servicio_id', 'Estado_id'];

    public function codesumi()
    {
        return $this->belongsTo('App\Modelos\Codesumi');
    }

    public function sedeproveedores()
    {
        return $this->belongsTo('App\Modelos\Sedeproveedore');
    }
}
