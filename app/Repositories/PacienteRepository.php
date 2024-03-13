<?php
namespace App\Repositories;

use App\Modelos\Paciente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PacienteRepository
{
    public function validacionRedvital($request){

        if(isset(Auth::user()->id)){
            $datos = Paciente::where('Num_Doc',$request->cedula)
            ->first();
        }else{
            $datos = "";
            $paciente = Paciente::where('Num_Doc',$request->cedula)->first();
            if($paciente){
                if(Hash::check($request->password, $paciente->password)){
                    $datos = $paciente;
                }
            }

        }

        return $datos;

    }

    public function datosPacienteRedvital($request){

        $data = Paciente::select('pacientes.Primer_Nom', 'pacientes.Primer_Ape', 'pacientes.Segundo_Ape', 'pacientes.Telefono',
            'pacientes.Num_Doc', 'pacientes.id', 'sedeproveedores.Nombre as IPS', 'pacientes.Ut', 'pacientes.Estado_Afiliado',
            'pacientes.Medicofamilia', 'pacientes.Tipo_Doc', 'pacientes.Tipo_Afiliado', 'pacientes.Telefono', 'pacientes.Correo1',
            'pacientes.Celular1', 'pacientes.Direccion_Residencia', 'prestadores.id as prestador_id', 'pacientes.entidad_id',
            'sedeproveedores.Telefono1 as telefonoIPS', 'sedeproveedores.Direccion as direccionIPS',
            'sedeproveedores.Telefono2 as telefono2IPS', 'users.name as nombreMedicoFamilia', 'users.apellido as apellidoMedicoFamilia',
            'users.email as correoMedicoFamilia', 'sedeproveedores.id as Sedeprestador_id')
            ->join('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
            ->join('prestadores', 'sedeproveedores.Prestador_id', 'prestadores.id')
            ->leftjoin('users', 'pacientes.Medicofamilia', 'users.id')
            ->where('Num_Doc',$request->cedula)
            ->first();

        return $data;
    }

}
