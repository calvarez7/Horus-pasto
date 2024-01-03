<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class PaqTemp extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    //
    protected $fillable = [
        'id','namePaq','reason','totalValueAf','repName','totalBills','code','Estado_id','creatorUser_id','auditorUser_id',
    ];

    public function ctTemps()
    {
        return $this->hasMany('App\Modelos\CtTemp','paq_temps_id');
    }

    public function afTemps()
    {
        return $this->hasMany('App\Modelos\AfTemp','paq_temps_id');
    }

    public function usTemps()
    {
        return $this->hasMany('App\Modelos\UsTemp','paq_temps_id');
    }

    public function amTemps()
    {
        return $this->hasMany('App\Modelos\AmTemp','paq_temps_id');
    }

    public function anTemps()
    {
        return $this->hasMany('App\Modelos\AnTemp','paq_temps_id');
    }

    public function auTemps()
    {
        return $this->hasMany('App\Modelos\AuTemp','paq_temps_id');
    }

    public function ahTemps()
    {
        return $this->hasMany('App\Modelos\AhTemp','paq_temps_id');
    }

    public function apTemps()
    {
        return $this->hasMany('App\Modelos\ApTemp','paq_temps_id');
    }

    public function acTemps()
    {
        return $this->hasMany('App\Modelos\AcTemp','paq_temps_id');
    }

    public function atTemps()
    {
        return $this->hasMany('App\Modelos\AtTemp','paq_temps_id');
    }

    public function creatorUser()
    {
        return $this->belongsTo('App\User','creatorUser_id');
    }

    public function estado()
    {
        return $this->belongsTo('App\Modelos\Estado','Estado_id');
    }

    public function deleteTempRip()
    {
        $this->ctTemps()->delete();
        $this->afTemps()->delete();
        $this->usTemps()->delete();
        $this->amTemps()->delete();
        $this->anTemps()->delete();
        $this->auTemps()->delete();
        $this->ahTemps()->delete();
        $this->apTemps()->delete();
        $this->acTemps()->delete();
        $this->atTemps()->delete();
    }

}
