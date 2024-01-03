<?php

namespace App\Console\Commands;

use App\Modelos\citapaciente;
use App\Services\CitasService;
use App\Services\FonoPlusWebService;
use Illuminate\Console\Command;

class NotificarCitaWhatsApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificarWhatsApp:cita';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia un mensaje de texto a los usuarios que tienen una cita programada.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        /** Comentario de prueba */
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cita_service = new CitasService();
        $citas = $cita_service->listarCitasWhatsApp();
        $service = new FonoPlusWebService();
        $service->whatsapp($citas);
    }
}
