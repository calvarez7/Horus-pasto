<?php

namespace App\Services;

use GuzzleHttp\Client;

class FonoPlusService {

    private $client;
    private $url_sms;
    private $token_sms;
    private $url_wpp;
    private $token_wpp;

    public function __construct(){
        $this->client = new Client();
        $this->url_sms = 'https://contacto-masivo.com/sms/back_sms/public/api/send/sms/json';
        $this->token_sms = '5yjq1kkr4eddl9gjonto1';
        $this->url_wpp = 'https://contacto-virtual.com/bot/chat_wa/ws/send/';
        $this->token_wpp = '63a3fc20fa676245b1207ebb6773c0c9b946b151';
    }

    /**
     * Envia un mensaje a whatsapp
     * @param String $telefono
     * @param String $mensaje
     * @param String $custom_field
     * @return ResponseInterface
     * @author David Peláez
     */
    public function whatsapp($telefono, $mensaje, $custom_field = null){
        return $this->client->post( $this->url_wpp, [
            'form_params' => [
                'login' => 'usuariosumimedical@gmail.com',
                'token' => $this->token_wpp,
                'message' => $mensaje,
                'mobile' => $telefono,
                'custom_field' => $custom_field

            ]
        ]);
    }

    /**
     * Envia un mensaje de texto
     * @param Array $data un array de objetos [ 'phone' , 'message']
     * @param String $tipo_envio
     * @return ResponseInterface
     * @author David Peláez
     */
    public function sms($data, $tipo_envio = '1via'){
        return $this->client->post($this->url_sms,
            [
                'json' => [
                    'token' => $this->token_sms,
                    'email' => 'sumimedical@fonoplus.com',
                    'type_send' => $tipo_envio,
                    'data' => [$data]
                ]
            ]
        );
    }

}
