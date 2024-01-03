<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileTrait{

    /** 
     * Genera un array de strings, desde una coleccion de objetos
     * @param Array $array
     * @param Array $array
     * @author David Peláez
     */
    public function generarArrayFile($consulta, $separador){
        $array = array();
        foreach ($consulta as $item){
            $cadena = implode($separador, $item);
            array_push($array, $cadena);
        }
        return $array;
    }

    /** 
     * Genera un archivo txt a partir de un array
     * @param Array $array
     * @return string $ruta
     * @author David Peláez
     */
    public function arrayATxt($array, $carpeta){
        /** Genera un nombre aleatorio para el archivo */
        $ruta_archivo = $carpeta . "/" . uniqid() . '.txt';
        foreach($array as $item){
            Storage::append($ruta_archivo, $item);
        }
        return $ruta_archivo;
    }
    

}