<?php

namespace App\Services;

use App\Models\Medicamento;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\UploadedFile;

/**
 * Servicio encargado de gestionar los medicamentos del catálogo,
 * incluyendo el listado de registros y la importación de datos
 * desde archivos Excel bajo el formato normado LINAME.
 */
class MedicamentoService
{
    /**
     * Obtiene el listado de todos los medicamentos ordenados alfabéticamente por nombre.
     * Soporta paginación nativa de Laravel.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listar()
    {
        return Medicamento::orderBy('nombre')->paginate(15);
    }

    /**
     * Procesa e importa un listado de medicamentos desde un archivo de Excel subido.
     * Verifica que el archivo cumpla con las columnas requeridas de la estructura LINAME.
     * Si el formato es válido, inserta o actualiza cada registro en la base de datos.
     *
     * @param \Illuminate\Http\UploadedFile $archivo Archivo Excel subido mediante el formulario.
     * @throws \Exception Si las cabeceras del archivo no coinciden exactamente con la estructura de LINAME.
     * @return array Detalle numérico de los registros importados y omitidos.
     */
    public function importarDesdeExcel(UploadedFile $archivo): array
    {
        // Almacenar temporalmente el archivo subido
        $ruta = $archivo->storeAs('temp', $archivo->getClientOriginalName());
        $rutaCompleta = storage_path('app/private/' . $ruta);

        // Cargar el documento usando la librería PhpSpreadsheet
        $spreadsheet = IOFactory::load($rutaCompleta);
        $hoja = $spreadsheet->getActiveSheet();
        $filas = $hoja->toArray();

        // Validar que sea formato oficial LINAME examinando la fila 5 (índice 4 en base 0)
        $encabezado = $filas[4] ?? [];
        $columnasEsperadas = ['Código', 'Co', 'di', 'go', 'Medicamento', 'Forma Farmacéutica', 'Concentración'];

        foreach ($columnasEsperadas as $index => $columna) {
            $valorActual = trim($encabezado[$index] ?? '');
            if ($valorActual !== $columna) {
                unlink($rutaCompleta); // Eliminar archivo temporal en caso de error
                throw new \Exception("El archivo no tiene el formato LINAME esperado. Se esperaba la columna '{$columna}' en la posición " . ($index + 1) . " pero se encontró '{$valorActual}'.");
            }
        }

        $importados = 0;
        $omitidos = 0;

        // Omitir cabeceras y procesar a partir de la fila 6 (índice 5 en adelante)
        foreach (array_slice($filas, 5) as $fila) {
            $codigo = trim($fila[0] ?? '');
            $nombre = trim($fila[4] ?? '');
            $forma  = trim($fila[5] ?? '');
            $conc   = trim($fila[6] ?? '');
            $precio = is_numeric($fila[7] ?? '') ? round((float)$fila[7], 2) : null;
            $aclar  = trim($fila[8] ?? '');

            // Si los campos obligatorios están vacíos, se omite la fila
            if (empty($codigo) || empty($nombre)) {
                $omitidos++;
                continue;
            }

            // Actualizar si existe por código único, o crear uno nuevo
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

        // Eliminar el archivo temporal una vez concluida la importación
        unlink($rutaCompleta);

        return [
            'importados' => $importados,
            'omitidos'   => $omitidos,
        ];
    }
}

