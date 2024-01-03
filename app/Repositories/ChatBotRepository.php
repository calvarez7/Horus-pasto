<?php

namespace App\Repositories;

use App\Modelos\ChatBot;

class ChatBotRepository {

    /**
     * Almacena un chat bot
     * @param Array $data
     * @return ChatBot
     */
    public function guardar($numero){
        return ChatBot::create([
            'numero' => $numero,
            'estancia' => 0,
        ]);
    }


}