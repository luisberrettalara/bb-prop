<?php

namespace Inmuebles\Console\Commands;

use Inmuebles\Services\Paquetes\PaquetesService;

use Illuminate\Console\Command;

class EnviarEmailFinalizacionCreditos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:enviar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviamos un mail a los anunciantes cuyos créditos están próximos a finalizar';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PaquetesService $service) {
        $procesados = $service->enviarMailCreditosProximosAFinalizar();
        
        $this->info('Se han enviado ' . $procesados . 'emails a los anunciantes');
    }
}
