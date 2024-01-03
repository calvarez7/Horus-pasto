<?php

namespace App\Jobs;

use App\Mail\FiasGeneradoMail;
use App\Traits\FileTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class GenerarFiasJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FileTrait;

    protected $instancia;
    protected $data;
    protected $usuario;
    
    public $failOnTimeout = false;
    public $timeout = 120000;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($instancia, $data, $usuario)
    {
        $this->instancia = $instancia;
        $this->data = $data;
        $this->usuario = $usuario;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** Se genera el archivo */
        $consulta = $this->instancia->repository->consultar($this->data);
        $array_strings = $this->instancia->service->generar($consulta);
        $archivo = $this->arrayATxt($array_strings, 'fias');

        Mail::to($this->usuario->email)
            ->send(new FiasGeneradoMail($this->data, $archivo));

        Storage::delete($archivo);

    }
}
