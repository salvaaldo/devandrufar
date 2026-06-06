<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Services\ClienteService;

/**
 * Controlador de Clientes.
 * Administra el catálogo de clientes comerciales que solicitan cotizaciones de medicamentos,
 * delegando la persistencia y paginación en el ClienteService.
 */
class ClienteController extends Controller
{
    /**
     * Constructor del controlador.
     * Inyecta el servicio de negocio encargado de la gestión de clientes.
     */
    public function __construct(private ClienteService $clienteService)
    {
    }

    /**
     * Muestra la lista paginada de clientes registrados.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $clientes = $this->clienteService->listar();
        return view('admin.clientes.index', compact('clientes'));
    }

    /**
     * Muestra la vista con el formulario para registrar un nuevo cliente.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.clientes.create');
    }

    /**
     * Almacena un nuevo cliente en el sistema.
     *
     * @param \App\Http\Requests\StoreClienteRequest $request Petición con datos validados del cliente.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreClienteRequest $request)
    {
        $this->clienteService->crear($request->validated());
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    /**
     * Muestra la vista con el formulario para editar a un cliente.
     *
     * @param \App\Models\Cliente $cliente Modelo del cliente a editar.
     * @return \Illuminate\View\View
     */
    public function edit(Cliente $cliente)
    {
        return view('admin.clientes.edit', compact('cliente'));
    }

    /**
     * Actualiza la información del cliente.
     *
     * @param \App\Http\Requests\UpdateClienteRequest $request Petición con datos validados.
     * @param \App\Models\Cliente $cliente Modelo del cliente a actualizar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $this->clienteService->actualizar($cliente, $request->validated());
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Realiza un borrado lógico (Soft Delete) del cliente.
     *
     * @param \App\Models\Cliente $cliente Modelo del cliente a eliminar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Cliente $cliente)
    {
        $this->clienteService->eliminar($cliente);
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}