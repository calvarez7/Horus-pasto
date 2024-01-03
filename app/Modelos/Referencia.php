<?php

namespace App\Modelos;

use App\Repositories\ReferenciaRepository;
use Illuminate\Database\Eloquent\Model;

class Referencia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = ['id_paci', 'id_prestador', 'tipo_anexo', 'Especialidad_remi', 'adjunto', 'state', 'rzeuz', 'tipo_solicitud', 'estadoconcurrencia_id'];

    public function cie10s()
    {
        return $this->belongsToMany('App\Modelos\Cie10', 'cie10_referencias');
    }

    public function adjuntos()
    {
        return $this->hasMany('App\Modelos\AdjuntoReferencia');
    }

    public function bitacora()
    {
        return $this->hasOne('App\Modelos\Bitacora');
    }

    public static function getRepository()
    {
        return new ReferenciaRepository();
    }

}
