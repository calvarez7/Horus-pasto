<?php

namespace App\Repositories;

use App\Modelos\Detaarticulorden;

class DetalleArticuloOrdenRepository
{

    public function setStateRevisionPharmacy($intCodigo,$strObservacion){
        $ordenDetalle = Detaarticulorden::where('Orden_id',$intCodigo);
        $ordenDetalle->update([
            'notaFarmacia' => $strObservacion,
            'Estado_id' => 35
        ]);
    }
}



