<?php

namespace App\Modelos;

use App\Repositories\DetalleArticuloOrdenRepository;
use Illuminate\Database\Eloquent\Model;

class Detaarticulorden extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Orden_id', 'codesumi_id', 'Estado_id', 'Cantidadosis', 'Via', 'Unidadmedida',
        'Frecuencia', 'Unidadtiempo', 'Duracion', 'Cantidadmensual', 'Cantidadmensualdisponible', 'NumMeses',
        'Cantidadpormedico', 'Observacion', 'Fechaorden','cantidadConcentracionFormulada','notaFarmacia',
        'frecuenciaDuracion', 'adomicilio', 'domiciliario','fecha_pendiente','firma_orden','mipres'
    ];

    public function detallearticulo()
    {
        return $this->belongsTo('App\Modelos\Detallearticulo', 'Orden_id');
    }

    public function orden()
    {
        return $this->belongsTo('App\Modelos\Orden');
    }

    public function codesumis()
    {
        return $this->belongsTo('App\Modelos\Codesumi', 'Codesumi_id');
    }

    public static function getRepository()
    {
        return new DetalleArticuloOrdenRepository();
    }
}
