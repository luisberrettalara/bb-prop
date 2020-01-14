<?php

namespace Inmuebles\Console\Commands;

use Inmuebles\Services\Paquetes\PaquetesService;

use Illuminate\Console\Command;

class DescontarCreditos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'creditos:descontar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Descontamos un día de los Créditos de Publicaciones';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PaquetesService $service) {
        $procesados = $service->descontarDiasDeCreditos();

        $this->info('Se han descontado créditos de ' . $procesados . ' publicaciones');
    }
}
