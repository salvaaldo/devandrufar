<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;
use App\Services\ProductoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/**
 * Controlador de Productos.
 * Proporciona el CRUD de los productos comerciales en el almacén,
 * integrando la búsqueda rápida y exportación en formato PDF horizontal.
 */
class ProductoController extends Controller
{
    /**
     * Constructor del controlador.
     * Inyecta el servicio que gestiona la persistencia de productos y su acoplamiento con LINAME.
     */
    public function __construct(private ProductoService $productoService)
    {
    }

    /**
     * Muestra la lista paginada de productos comerciales.
     * Permite filtrar por texto basándose en nombre, código o marca.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Producto::with('medicamento')->orderBy('nombre');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('codigo', 'LIKE', "%{$search}%")
                  ->orWhere('marca', 'LIKE', "%{$search}%");
            });
        }

        $productos = $query->paginate(15)->withQueryString();
        return view('admin.productos.index', compact('productos'));
    }

    /**
     * Muestra el formulario para registrar un nuevo producto.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.productos.create');
    }

    /**
     * Guarda un producto en la base de datos sincronizando los datos base de LINAME.
     *
     * @param \App\Http\Requests\StoreProductoRequest $request Datos validados del formulario.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductoRequest $request)
    {
        $this->productoService->crear($request->validated());
        return redirect()->route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    /**
     * Muestra el formulario de edición de un producto existente.
     *
     * @param \App\Models\Producto $producto Producto a editar.
     * @return \Illuminate\View\View
     */
    public function edit(Producto $producto)
    {
        return view('admin.productos.edit', compact('producto'));
    }

    /**
     * Actualiza el producto comercial y re-sincroniza los campos de LINAME si cambió el medicamento.
     *
     * @param \App\Http\Requests\UpdateProductoRequest $request Datos validados de actualización.
     * @param \App\Models\Producto $producto Producto a actualizar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $this->productoService->actualizar($producto, $request->validated());
        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Realiza un borrado lógico (Soft Delete) del producto comercial.
     *
     * @param \App\Models\Producto $producto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Producto $producto)
    {
        $this->productoService->eliminar($producto);
        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    /**
     * Endpoint AJAX para buscar y retornar los datos completos de un medicamento del catálogo.
     * Facilita el autocompletado en el formulario al asociar un medicamento.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarMedicamento(Request $request)
    {
        $medicamento = $this->productoService->buscarMedicamento($request->id);
        if ($medicamento) {
            return response()->json($medicamento);
        }
        return response()->json(null, 404);
    }

    /**
     * Genera y transmite el PDF del catálogo completo de productos registrados.
     * Configura la hoja en formato horizontal (landscape) A4.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pdf()
    {
        $productos = Producto::with('medicamento')->orderBy('nombre')->get();

        $pdf = Pdf::loadView('admin.productos.pdf', compact('productos'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('catalogo-productos-' . now()->format('d-m-Y') . '.pdf');
    }
}