<?php

namespace App\Services\Fias;

class F2AService {

    /**
     * Genera y almacena el archivo txt para descargar
     * @param Collection $consulta
     * @return String $ruta
     * @author David Peláez
     */
    public function generar($consulta){

        $cadena = array();
        
        foreach($consulta as $departamento => $municipios){
        
            foreach($municipios as $municipio => $codigos_cie10){

                foreach ($codigos_cie10 as $codigo_cie10 => $paciente){
                    array_push($cadena, $departamento . '|' 
                    . $municipio . '|' 
                    . $codigo_cie10 . '|' 
                    . $paciente["0-5"]["F"] . '|' 
                    . $paciente["0-5"]["M"] . '|'
                    . $paciente["6-11"]["F"] . '|' 
                    . $paciente["6-11"]["M"] . '|'
                    . $paciente["12-17"]["F"] . '|' 
                    . $paciente["12-17"]["M"] . '|'
                    . $paciente["18-28"]["F"] . '|' 
                    . $paciente["18-28"]["M"] . '|'
                    . $paciente["29-34"]["F"] . '|' 
                    . $paciente["29-34"]["M"] . '|'
                    . $paciente["35-39"]["F"] . '|' 
                    . $paciente["35-39"]["M"] . '|'
                    . $paciente["40-44"]["F"] . '|' 
                    . $paciente["40-44"]["M"] . '|'
                    . $paciente["45-49"]["F"] . '|' 
                    . $paciente["45-49"]["M"] . '|'
                    . $paciente["50-54"]["F"] . '|' 
                    . $paciente["50-54"]["M"] . '|'
                    . $paciente["55-59"]["F"] . '|' 
                    . $paciente["55-59"]["M"] . '|'
                    . $paciente["60-64"]["F"] . '|' 
                    . $paciente["60-64"]["M"] . '|'
                    . $paciente["65-69"]["F"] . '|' 
                    . $paciente["65-69"]["M"] . '|'
                    . $paciente["70"]["F"] . '|' 
                    . $paciente["70"]["M"] . '|'
                    . $paciente["total"]);
                }
            }
        }
        
        return $cadena;
    }

}