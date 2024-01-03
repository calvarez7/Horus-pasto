<?php

namespace App\Modelos;

use App\Repositories\CodesumiRepository;
use Illuminate\Database\Eloquent\Model;

class Codesumi extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $guarded = [];

    public function codepropio()
    {
        return $this->hasMany('App\Modelos\Codepropio');
    }

    public function detallearticulos()
    {
        return $this->hasMany('App\Modelos\Detallearticulo', 'Codesumi_id');
    }

    public function detaarticulordens()
    {
        return $this->hasMany('App\Modelos\Detaarticulorden', 'Codesumi_id');
    }

    public static function getRepository()
    {
        return new CodesumiRepository();
    }

    public function viaadministracion()
    {
        return $this->hasMany('App\Modelos\CodesumiViadministracion', 'codesumi_id');
    }
}
