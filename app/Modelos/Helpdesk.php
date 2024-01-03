<?php

namespace App\Modelos;


use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Helpdesk extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'User_id', 'Sede', 'Subarea_id', 'Categoria_id', 'Estado_id',
        'Requerimiento', 'Asunto', 'Descripcion', 'Cargo_user', 'area_id',
        'categoria_id', 'prioridad','tiempo_estimado','calificacion'
    ];
    protected $appends = [
        'diferencia_dias',
        'fecha_actual',
    ];

    public function Gestion_helpdesks()
    {
        return $this->hasMany('App\Modelos\Gestions_help', 'Helpdesk_id');
    }

    public function Accionhelpdesks()
    {
        return $this->hasMany('App\Modelos\Gestions_help', 'Helpdesk_id');
    }

    public function Permisohelpdesks()
    {
        return $this->hasMany('App\Modelos\Asignado_helpdesk', 'helpdesk_id');
    }

    /**
     * obtiene dias entre dos fechas
     * @return integer
     */
    public function getDiferenciaDiasAttribute(){
        $hoy = Carbon::now();
        $estimado = Carbon::parse($this->tiempo_estimado);
        return $estimado->diffInDays($hoy);
    }

    /**
     * obtiene fecha actual
     * @return integer
     */
    public function getFechaActualAttribute(){
        return Carbon::now();
    }

}
