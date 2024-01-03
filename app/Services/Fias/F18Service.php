<?php

namespace App\Services\Fias;

class F18Service {

    /**
     * Genera y almacena el archivo txt para descargar
     * @param Collection $consulta
     * @return String $ruta
     * @author David Peláez
     */
    public function generar($consulta){
        $array = array();
        foreach($consulta as $item){
            array_push($array, implode('|', $item->toArray()));
        }
        return $array;
    }

}