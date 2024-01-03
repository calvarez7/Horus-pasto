<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class FonoPlusWebService {

    private $client;
    private $urlMensaje;
    private $urlRespuesta;
    private $headers;

    public function __construct(){
        $this->client = new Client();
        $this->urlMensaje = 'https://backend.jirka.co:10070/sasha-gateway/ms-wappit/public/api/v1/whatsapp-messages/bulk';
        $this->urlRespuesta = 'https://backend.jirka.co:10070/sasha-gateway/ms-wappit/public/api/v1/whatsapp-messages/reply';
        $this->headers = [
            'X-Token' => '65df2715-b60f-46b9-9dc1-0d78323c6f74',
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Envia un mensaje de whatsapp atravez de un plantilla definida
     * @param string $numero
     */
    public function whatsapp($citas){

        /** Organizo los headers */
        $mensajes = [];
        foreach ($citas as $cita) {
            if($cita->numero && strlen($cita->numero) === 10){
            $mensaje = [
                'externalId' => $cita->historia,
                'mobileNumber' => '+57' . $cita->numero,
                'identification' => $cita->Num_Doc,
                'data' => [
                                [
                                    "field" => "NOMBRE",
                                    "value" => $cita->nombre_paciente,
                                ],
                                [
                                    "field" => "ACTIVIDAD",
                                    "value" => $cita->actividad,
                                ],
                                [
                                    "field" => "ESPECIALIDAD",
                                    "value" => $cita->especialidad,
                                ],
                                [
                                    "field" => "FECHA",
                                    "value" => $cita->fecha,
                                ],
                                [
                                    "field" => "MEDICO",
                                    "value" => $cita->nombre_medico,
                                ],
                                [
                                    "field" => "HORA",
                                    "value" => $cita->hora,
                                ],
                                [
                                    "field" => "SEDE",
                                    "value" => $cita->nombreSede,
                                ],
                                [
                                    "field" => "DIRECCION",
                                    "value" => $cita->direccionSede,
                                ]
                            ]
            ];

            $mensajes[] = $mensaje;
            }
        }
        $lote = [
            'headers' => $this->headers,
            'body' => json_encode(
                        [
                            'templateId' => 116,
                            'messages' => $mensajes
                        ])
        ];
        return $this->client->post($this->urlMensaje,$lote);
    }

    /**
     * respuestaWhatsApp sirve para responder segun la solicitud del afiliado ya se Cancelar o Confirmar cita
     *
     * @param  mixed $var
     * @return void
     */
    public function respuestaWhatsApp($cita_paciente_id, $numero, $mensaje)
    {
        return $this->client->post($this->urlRespuesta, [
            'headers' => $this->headers,
            'body' => json_encode(
                [

                    'externalId' => $cita_paciente_id,
                    'templateId' => 116, // pendiente a cambio
                    'mobileNumber' => $numero,
                    'data' => [
                        [
                            'field' => 'message',
                            'value' => $mensaje
                        ]
                    ]
                ]
            )
        ]);
    }

}
