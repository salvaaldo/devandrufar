<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Services\ClienteService;

class ClienteController extends Controller
{
    public function __construct(private ClienteService $clienteService)
    {
    }

    public function index()
    {
        $clientes = $this->clienteService->listar();
        return view('admin.clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(StoreClienteRequest $request)
    {
        $this->clienteService->crear($request->validated());
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $this->clienteService->actualizar($cliente, $request->validated());
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $this->clienteService->eliminar($cliente);
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}