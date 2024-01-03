<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tutelas extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Paciente_id', 'Rol_id', 'Tipo_requerimiento_id',
        'Observacion', 'Direccion', 'Integralidad', 'Exclusion', 'Juzgado_id', 'Radicado', 'Municipio_id', 'Telefono'];

    public function Adjuntos_tutelas()
    {
        return $this->hasMany('App\Modelos\Adjuntos_tutelas', 'Tutela_id');
    }

    public function Tiposerviciotutelas()
    {
        return $this->hasMany('App\Modelos\Tiposerviciotutela', 'Tutela_id');
    }

    public function Exclusiontutelas()
    {
        return $this->hasMany('App\Modelos\Exclusiontutela', 'Tutela_id');
    }

    public function Medicamentotutelas()
    {
        return $this->hasMany('App\Modelos\Medicamentotutela', 'Tutela_id');
    }

    public function Cuptutelas()
    {
        return $this->hasMany('App\Modelos\Cuptutela', 'Tutela_id');
    }

    public function Respuestatutelas()
    {
        return $this->hasMany('App\Modelos\Respuestatutela', 'Tutela_id');
    }

    public function Roltutelas()
    {
        return $this->hasMany('App\Modelos\Roltutela', 'Tutela_id');
    }
}
