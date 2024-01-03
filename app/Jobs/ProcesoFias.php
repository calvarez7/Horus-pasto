<?php

namespace App\Jobs;

use App\Traits\FileTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcesoFias implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FileTrait;

    protected $instancia;
    protected $consulta;
    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($instancia, $consulta, $request)
    {
        $this->instancia = $instancia;
        $this->consulta = $consulta;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $array = $this->instancia->service->estructurar($this->consulta, $this->request);
        $ruta = $this->arrayATxt($array, 'fias');
    }
}
