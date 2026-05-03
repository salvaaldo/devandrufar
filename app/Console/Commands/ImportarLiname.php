<?php

namespace App\Console\Commands;

use App\Models\Medicamento;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportarLiname extends Command
{
    protected $signature = 'importar:liname {archivo}';
    protected $description = 'Importa medicamentos desde el archivo Excel LINAME';

    public function handle()
    {
        $archivo = $this->argument('archivo');

        if (!file_exists($archivo)) {
            $this->error("El archivo no existe: $archivo");
            return 1;
        }

        $this->info('Cargando archivo Excel...');
        $spreadsheet = IOFactory::load($archivo);
        $hoja = $spreadsheet->getActiveSheet();
        $filas = $hoja->toArray();

        $importados = 0;
        $omitidos = 0;

        // Empezamos desde la fila 6 (índice 5) que es donde están los datos
        foreach (array_slice($filas, 5) as $fila) {
            $codigo = trim($fila[0] ?? '');
            $nombre = trim($fila[4] ?? '');
            $forma  = trim($fila[5] ?? '');
            $conc   = trim($fila[6] ?? '');
            $precio = is_numeric($fila[7] ?? '') ? round((float)$fila[7], 2) : null;
            $aclar  = trim($fila[8] ?? '');

            // Saltar filas sin código o nombre
            if (empty($codigo) || empty($nombre)) {
                $omitidos++;
                continue;
            }

            // Insertar o actualizar
            Medicamento::updateOrCreate(
                ['codigo' => $codigo],
                [
                    'nombre'             => $nombre,
                    'forma_farmaceutica' => $forma ?: null,
                    'concentracion'      => $conc ?: null,
                    'precio_referencial' => $precio,
                    'aclaracion'         => $aclar ?: null,
                ]
            );

            $importados++;
        }

        $this->info("Importación completada: $importados medicamentos importados, $omitidos filas omitidas.");
        return 0;
    }
}