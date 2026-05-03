<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MedicamentoService;
use App\Models\Medicamento;
use Illuminate\Http\Request;

class MedicamentoController extends Controller
{
    public function __construct(private MedicamentoService $medicamentoService) {}

    public function index()
    {
        $medicamentos = $this->medicamentoService->listar();
        return view('admin.medicamentos.index', compact('medicamentos'));
    }

    public function importar()
    {
        return view('admin.medicamentos.importar');
    }

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
            $resultado = $this->medicamentoService->importarDesdeExcel($request->file('archivo'));
            return redirect()->route('medicamentos.index')
                ->with('success', "Importación completada: {$resultado['importados']} medicamentos importados, {$resultado['omitidos']} filas omitidas.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(Medicamento $medicamento)
    {
        $this->medicamentoService->eliminar($medicamento);
        return redirect()->route('medicamentos.index')
            ->with('success', 'Medicamento eliminado correctamente.');
    }
}
