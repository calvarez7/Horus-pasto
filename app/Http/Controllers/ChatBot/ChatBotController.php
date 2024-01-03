<?php

namespace App\Http\Controllers\ChatBot;

use App\Http\Controllers\Controller;
use App\Modelos\ChatBot;
use App\Modelos\Paciente;
use App\Repositories\ChatBotRepository;
use App\Services\ChatBot\ChatBotService;
use Error;
use Exception;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    
    private $service;
    private $repository;

    public function __construct(){   
        $this->service = new ChatBotService();
        $this->repository = new ChatBotRepository;
    }

    /**
     * Envia un mensaje
     */
    public function peticion(Request $request){
        try{
            /** recuperamos el numero entrante */
            $numero = explode(':', $request->input('From'))[1];
            $respuesta = $request->input('Body');

            /** Consultamos si existe el numero */
            $chatbot = ChatBot::where('numero', $numero)->first();
            $estancia = 0;
            if($chatbot){ // Si existe recuperamos su ultima estancia
                $estancia = $chatbot->estancia;
            }else{ // Si no, creamos uno y le asignamos la estancia 0
                $chatbot = $this->repository->guardar($numero);
            }
            $mensaje = $this->service->determinarAccion($estancia, $respuesta, $chatbot);
            /** Se envia la informacion recuperada */
            $this->service->enviar($mensaje, $numero);

            return true;
        }catch(\Throwable $th){
            $this->service->enviar($th->getMessage(), $numero);
            return false;
        }
    }

}