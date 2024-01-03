<?php

namespace App\Services\ChatBot;

use App\Modelos\citapaciente;
use App\Modelos\Paciente;
use Twilio\Rest\Client;
use Carbon\Carbon;
use Error;
use Illuminate\Support\Facades\DB;

class ChatBotService {

    public $client;
    public $twilio_whatsapp_number;
    public $account_sid;
    public $auth_token;
    public $menu_principal = "Las siguientes opciones \n
        1. consultar";

    public function __construct(){
        $this->twilio_whatsapp_number = "+14155238886";
        $this->account_sid = "ACf90a7fce3bba45ec0ea41249829d7bc5";
        $this->auth_token = "f6229b36b821629db68f916e3f0e1bcb";
        $this->client = new Client($this->account_sid, $this->auth_token);
    }

    /**
     * Envia un mensaje de WhatsApp
     * @param string $mensaje cuerpo del sms
     * @param string $destino numero que recibe
     * @return
     * @author David Peláez
     */
    public function enviar(string $mensaje, string $destino){
        return $this->client->messages->create("whatsapp:$destino", [
                'from' => "whatsapp:$this->twilio_whatsapp_number",
                'body' => $mensaje
            ]);
    }

    /**
     * Determina la accion a arealizar en el chatbot
     * @param int $estancia en la que se encuentra
     * @param string $respuesta respuesta enviada por el usuario
     * @param ChatBot $chatbot modelo del numero entrante
     * @return string
     * @author David Peláez
     */
    public function determinarAccion(int $estancia, string $respuesta, $chatbot){

        $mensaje = ""; // Inicializo el mensaje a enviar
        switch ($estancia) {
            case 0:
                if($respuesta === '1' || strtolower($respuesta) === 'acepto' ){
                    $chatbot->update([ 'estancia' => 1]);
                    $mensaje = "Menu principal";
                    break;
                }
                $mensaje = "Mensaje de aceptacion para tratar de datos" . "\n";
                $mensaje .= "0. Cancelar" . "\n";
                $mensaje .= "1. Acepto";
                break;
            case 1:
                if(strlen($respuesta) > 5){
                }
                $mensaje = "Digita tu numero de documento para consultar las citas.";
                break;
            default:
                $mensaje = "Estancia diferente";
                break;
        }

        return $mensaje;
        
    }
        
    /**
     * Valida que el campo documento haya sido enviado y que el paciente exista, de existir rotarta el paciente
     * @param $documento
     * @return Paciente $paciente
     * @author David Peláez
     */
    public function validarPaciente($documento){
        if(!$documento){
            throw new Error('Se debe ingresar un documento.');
        }
        /** Se consulta el paciente */
        $paciente = Paciente::where('Num_Doc', $documento)->first();
        if(!$paciente){
            throw new Error('Este documento no es valido.');
        }

        return $paciente;
    }

    /**
     * Valida que el paciente tenga citas
     * @param $paciente_id
     * @return citapaciente $citas
     * @author David Peláez
     */
    public function validarCitas($paciente_id){
        /** mayor a la fecha de hoy y diferente de 9 */
        $citas = DB::table('cita_paciente')
            ->where('cita_paciente.Paciente_id', $paciente_id)
            ->whereDate('cita_paciente.created_at', '>=', Carbon::now())
            ->where('cita_paciente.estado_id', '!=', 9)
            ->join('citas', 'cita_paciente.Cita_id', '=', 'citas.id')
            ->join('agendas', 'citas.Agenda_id', '=', 'agendas.id')
            ->join('consultorios', 'agendas.Consultorio_id', '=', 'consultorios.id')
            ->join('sedes', 'consultorios.Sede_id', '=', 'sedes.id')
            ->join('agendausers', 'agendas.id', '=', 'agendausers.Agenda_id')
            ->join('users', 'agendausers.Medico_id', '=', 'users.id')
            ->join('especialidade_tipoagenda', 'agendas.Especialidad_id', '=', 'especialidade_tipoagenda.id')
            ->join('especialidades', 'especialidade_tipoagenda.Especialidad_id', '=', 'especialidades.id')
            ->join('tipo_agendas', 'especialidade_tipoagenda.Tipoagenda_id', '=', 'tipo_agendas.id')
            ->select(
                'sedes.Nombre as nombre_sede',
                'citas.Hora_Inicio as fecha',
                'citas.id as id',
                'users.name as nombre_medico',
                'especialidades.Nombre as nombre_especialidad',
                'tipo_agendas.name as nombre_tipo_agenda'
            )
            ->get();

        if(!$citas){
            throw new Error('No cuentas con citas');
        }

        return $citas;
    }



}