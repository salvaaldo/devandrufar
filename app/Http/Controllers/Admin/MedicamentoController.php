<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MedicamentoService;
use App\Models\Medicamento;
use Illuminate\Http\Request;

/**
 * Controlador de Medicamentos.
 * Administra el catálogo oficial normativo de medicamentos (LINAME),
 * incluyendo la carga masiva e importación desde planillas Excel de forma segura.
 */
class MedicamentoController extends Controller
{
    /**
     * Constructor del controlador.
     * Inyecta el servicio que contiene la lógica de negocio y validación de los archivos LINAME.
     */
    public function __construct(private MedicamentoService $medicamentoService) {}

    /**
     * Muestra la lista paginada del catálogo de medicamentos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $medicamentos = $this->medicamentoService->listar();
        return view('admin.medicamentos.index', compact('medicamentos'));
    }

    /**
     * Muestra la vista con el formulario para subir la plantilla de Excel.
     *
     * @return \Illuminate\View\View
     */
    public function importar()
    {
        return view('admin.medicamentos.importar');
    }

    /**
     * Procesa la subida y lectura del archivo Excel de LINAME.
     * Realiza validaciones del tipo de archivo y tamaño máximo (10MB),
     * delegando la lectura al servicio. Si hay errores de cabecera o estructura,
     * retorna con un mensaje descriptivo para el usuario.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function procesarImportacion(Request $request)
    {
        $request->validate([
            'archivo' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ], [
            'archivo.required' => 'Debes seleccionar un archivo.',
            'archivo.mimes'    => 'El archivo debe ser Excel (.xlsx o .xls).',
            'archivo.max'      => 'El archivo no debe superar 10MB.',
        ]);

        try {
            // Llamar al método de importación del servicio de medicamentos
            $resultado = $this->medicamentoService->importarDesdeExcel($request->file('archivo'));
            
            return redirect()->route('medicamentos.index')
                ->with('success', "Importación completada: {$resultado['importados']} medicamentos importados, {$resultado['omitidos']} filas omitidas.");
        } catch (\Exception $e) {
            // Capturar errores del formato del archivo Excel y reportar en pantalla
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Elimina físicamente un medicamento del catálogo general.
     *
     * @param \App\Models\Medicamento $medicamento
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Medicamento $medicamento)
    {
        $medicamento->delete();
        return redirect()->route('medicamentos.index')
            ->with('success', 'Medicamento eliminado correctamente.');
    }
}

