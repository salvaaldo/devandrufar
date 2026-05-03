<?php

namespace App\Services;

use App\Models\Cliente;

class ClienteService
{
    public function listar()
    {
        return Cliente::orderBy('nombre')->paginate(10);
    }

    public function crear(array $datos): Cliente
    {
        return Cliente::create($datos);
    }

    public function actualizar(Cliente $cliente, array $datos): Cliente
    {
        $cliente->update($datos);
        return $cliente;
    }

    public function eliminar(Cliente $cliente): void
    {
        $cliente->delete();
    }
}