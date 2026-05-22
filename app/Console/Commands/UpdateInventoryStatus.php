<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\InventarioService;

class UpdateInventoryStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el estado (vigente, por vencer, vencido) de todos los lotes de inventario';

    /**
     * Execute the console command.
     */
    public function handle(InventarioService $inventarioService)
    {
        $this->info('Iniciando actualización de estados de inventario...');
        
        $actualizados = $inventarioService->actualizarEstados();
        
        $this->info("Proceso completado. Se actualizaron $actualizados registros.");
    }
}
