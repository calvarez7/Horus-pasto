<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DetalleSolicitudBodega extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    //
    protected $table    = 'detalle_solicitud_bodega';
    protected $fillable = [
        'Usuarioresponde_id', 'Bodegarticulo_id', 'Lote', 'Cantidad', 'Codesumi_id', 'SolicitudBodega_id', 'Fvence', 'Estado_id','cantidad_aprobada','lote_referencia_id','detallearticulo_id'
    ];
}
