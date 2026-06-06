<?php

namespace App\Services;

use App\Models\Cliente;

/**
 * Servicio encargado de gestionar el registro y mantenimiento de los clientes.
 * Proporciona métodos para listar, crear, actualizar y eliminar clientes en la base de datos.
 */
class ClienteService
{
    /**
     * Obtiene el listado paginado de los clientes registrados, ordenados alfabéticamente por su nombre.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listar()
    {
        return Cliente::orderBy('nombre')->paginate(10);
    }

    /**
     * Registra un nuevo cliente en el sistema.
     *
     * @param array $datos Datos estructurados del cliente (nombre, nit_ci, telefono, direccion, etc.).
     * @return \App\Models\Cliente El cliente creado.
     */
    public function crear(array $datos): Cliente
    {
        return Cliente::create($datos);
    }

    /**
     * Actualiza los datos de un cliente existente.
     *
     * @param \App\Models\Cliente $cliente Modelo del cliente a actualizar.
     * @param array $datos Nuevos datos provistos por el usuario.
     * @return \App\Models\Cliente El cliente actualizado.
     */
    public function actualizar(Cliente $cliente, array $datos): Cliente
    {
        $cliente->update($datos);
        return $cliente;
    }

    /**
     * Elimina físicamente un cliente de la base de datos.
     *
     * @param \App\Models\Cliente $cliente Modelo del cliente a eliminar.
     * @return void
     */
    public function eliminar(Cliente $cliente): void
    {
        $cliente->delete();
    }
}