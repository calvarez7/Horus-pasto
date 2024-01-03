<?php 

namespace App\Services;

use App\Modelos\Departamento;
use App\Modelos\Municipio;
use App\Modelos\Sedeproveedore;
use App\User;
use Carbon\Carbon;

class PacienteService {

    /**
     * Genera una estructura HL7
     * @param Objetc $data
     * @return Object
     * @author David Peláez
     */
    public function generarEstructuraHL7($data){
        
        return (Object)[
            "resourceType" => "Patient",
            "active" => $this->determinarEstado($data->Estado_Afiliado), //estado del paciente,
            "region" => $data->Region, //region la que pertenece el paciente
            "ut" => $data->Ut, //ut del paciente
            "name" => $this->generarNombreCompleto($data->Primer_Nom, $data->SegundoNom, $data->Primer_Ape, $data->Segundo_Ape), //nombre completo paciente
            "document_type" => $data->Tipo_Doc, //tipo documento paciente (CC,TI,RC,CE,PEP,DNI,SCR,PA)
            "document" => $data->Num_Doc,//cedula del paciente
            "phone" => $data->Telefono, //celular del paciente
            "email" => $data->Correo1, //email del paciente
            "gender" => $data->Sexo, //sexo del paciente (F,M)
            "date_membership" => $data->Fecha_Afiliado, //fecha afiliacion (año/mes/dia)
            "birthDate" => $data->Fecha_Naci, // fecha nacimiento (año/mes/dia)
            "age" => intval($data->Edad_Cumplida), //edad cumplida
            "incapacity" => strtolower($data->Discapacidad) != "sin discapacidad", //tiene discapacidad
            "incapacity_degree" => $data->Grado_Discapacidad,// grado de incapacidad
            "membership_type" => strtolower($data->Tipo_Afiliado),// tipo de afiliado (cotizante,beneficiario)
            "ips_primary" => $this->getNombreIps($data->IPS), //ips primaria del paciente
            "orden judicial" => $data->Orden_Judicial, // paciente con tutela
            "folio_number" => $data->Num_Folio, //numero de radicado tutela
            "emission_date" => $data->Fecha_Emision, // fecha emision de la tutela
            "relationship" => $data->Parentesco, // prentesco
            "contributor_document_type" => $data->TipoDoc_Cotizante, //tipo documento del cotizante (CC,TI,RC,CE,PEP,DNI,SCR,PA)
            "contributor_document" => $data->Doc_Cotizante, // documento del cotizante
            "contributor_type" => $data->Tipo_Cotizante, // tipo de cotizante
            "dane_municipality" => $this->getNombreMunicipio($data->Mpio_Atencion), // municipio dane
            "membership_municipality" => $data->Mpio_Afiliado, //municipio de afiliado
            "adress" => $data->Direccion_Residencia, // direccion del paciente
            "dane_department" => $data->Departamento, // departamento del dane 
            "residence_department" => $this->getNombreDepartamento($data->Dpto_Atencion), // departamento recidencia
            "residence_municipality" => $data->Mpio_Residencia, // municipio recidencia
            "supplementary_data" => [
                "origen_region" => null, //region origen
                "destination_region" => null, //region de destino
                "start_date" => null, //fecha inicio
                "end_date" => null, //fecha final
                "assignat_ips" => null //ips asignada
            ]
        ];
            
        /*
        return (Object)[
            "resourceType" => "Patient",
            "identifier" => $data->Num_Doc, 
            "active" => $this->determinarEstado($data->Estado_Afiliado),
            "name" => $this->generarNombreCompleto($data->Primer_Nom, $data->Segundo_Nom, $data->Primer_Ape, $data->Segundo_Ape),
            "telecom" => $data->Telefono,
            "gender" => $data->Sexo,
            "birthDate" => $data->Fecha_Naci,
            "address" => $data->Direccion_Residencia,
            "maritalStatus" => $data->estado_civil,
            "multipleBirthBoolean" => null,
            "multipleBirthInteger" => null,
            "photo" => null,
            "contact" => (Object)[
                "relationship" => null,
                "name" => null,
                "telecom" => null,
                "address" => null,
                "gender" => null,
                "organization" => null,
                "period" => null
            ],
            "communication" => [
                "language" => "español",
                "preferred" => "español"
            ],
            "generalPractitioner" => User::where('id', intval($data->Medicofamilia))->first(),
            "managingOrganization" => $data->Ut,
            "link" => (Object)[
                "other" => null,
                "type" => null
            ]
        ];
        */
    }

    /**
     * Determina el estado del afiliado segun el codgo de estado y retorna un booleano
     * @param String $estado_id
     * @return boolean
     * @author David Peláez
     */
    public function determinarEstado($estado_id){
        return intval($estado_id) != 1 ? false : true;
    }

    /**
     * Genera un nombre completo 
     * @param splitoperator
     * @return String
     * @author David Peláez
     */
    public function generarNombreCompleto(...$nombres){
        return implode(' ', $nombres);
    }

    /**
     * formatear fecha a HL7
     * @param date $fecha
     * @return date
     * @author David Peláez
     */
    public function formatearFechaHL7($fecha){
        dd(Carbon::createFromFormat('Y/m/d', $fecha)->format('Y/m/d'));
    }

    /**
     * obtener relacion con sede provedor
     * @param sede_id $sede_id
     * @return string
     * @author David Peláez
     */
    public function getNombreIps($sede_id){
        $sede = Sedeproveedore::where('id', intval($sede_id))->first();
        return $sede->Nombre ?? null;
    }

    /**
     * Busca el municipio, retorna el nombre
     * @param municipio_id
     * @return string
     * @author David Peláez
     */
    public function getNombreMunicipio($municipio_id){
        $municipio = Municipio::where('id', $municipio_id)->first();
        return $municipio->Nombre ?? null;
    }

    /**
     * Busca el municipio, retorna el nombre
     * @param departamento_id
     * @return string
     * @author David Peláez
     */
    public function getNombreDepartamento($departamento_id){
        $departamento = Departamento::where('id', $departamento_id)->first();
        return $departamento->Nombre ?? null;
    }

}