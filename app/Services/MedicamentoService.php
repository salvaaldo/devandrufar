<?php

namespace App\Services;

use App\Models\Medicamento;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\UploadedFile;

class MedicamentoService
{
    public function listar()
    {
        return Medicamento::orderBy('nombre')->paginate(15);
    }

    public function importarDesdeExcel(UploadedFile $archivo): array
    {
        $ruta = $archivo->storeAs('temp', $archivo->getClientOriginalName());
        $rutaCompleta = storage_path('app/private/' . $ruta);

        $spreadsheet = IOFactory::load($rutaCompleta);
        $hoja = $spreadsheet->getActiveSheet();
        $filas = $hoja->toArray();

        // Validar que sea formato LINAME
        $encabezado = $filas[4] ?? [];
        $columnasEsperadas = ['Código', 'Co', 'di', 'go', 'Medicamento', 'Forma Farmacéutica', 'Concentración'];

        foreach ($columnasEsperadas as $index => $columna) {
            $valorActual = trim($encabezado[$index] ?? '');
            if ($valorActual !== $columna) {
                unlink($rutaCompleta);
                throw new \Exception("El archivo no tiene el formato LINAME esperado. Se esperaba la columna '{$columna}' en la posición " . ($index + 1) . " pero se encontró '{$valorActual}'.");
            }
        }

        $importados = 0;
        $omitidos = 0;

        foreach (array_slice($filas, 5) as $fila) {
            $codigo = trim($fila[0] ?? '');
            $nombre = trim($fila[4] ?? '');
            $forma  = trim($fila[5] ?? '');
            $conc   = trim($fila[6] ?? '');
            $precio = is_numeric($fila[7] ?? '') ? round((float)$fila[7], 2) : null;
            $aclar  = trim($fila[8] ?? '');

            if (empty($codigo) || empty($nombre)) {
                $omitidos++;
                continue;
            }

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

        unlink($rutaCompleta);

        return [
            'importados' => $importados,
            'omitidos'   => $omitidos,
        ];
    }
}
