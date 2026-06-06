<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\Medicamento;

/**
 * Servicio encargado de gestionar los productos comerciales del sistema.
 * Se encarga de la sincronización de campos clave (nombre, forma farmacéutica, concentración y precio)
 * desde el catálogo oficial de medicamentos (LINAME).
 */
class ProductoService
{
    /**
     * Obtiene una lista paginada de todos los productos comerciales.
     * Carga de forma anticipada la relación con el medicamento base de LINAME.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listar()
    {
        return Producto::with('medicamento')->orderBy('nombre')->paginate(15);
    }

    /**
     * Registra un nuevo producto comercial en la base de datos.
     * Recupera automáticamente el nombre, forma farmacéutica, concentración y precio referencial
     * desde el medicamento del catálogo oficial (LINAME) antes de realizar el guardado.
     *
     * @param array $datos Datos provenientes del formulario de registro (incluye medicamento_id y datos comerciales adicionales).
     * @return \App\Models\Producto El registro del producto comercial creado.
     */
    public function crear(array $datos): Producto
    {
        // Obtener el medicamento de referencia del catálogo oficial
        $medicamento = Medicamento::findOrFail($datos['medicamento_id']);
        
        // Sincronizar campos obligatorios desde LINAME
        $datos['nombre']             = $medicamento->nombre;
        $datos['forma_farmaceutica'] = $medicamento->forma_farmaceutica;
        $datos['concentracion']      = $medicamento->concentracion;
        $datos['precio_referencial'] = $medicamento->precio_referencial;

        return Producto::create($datos);
    }

    /**
     * Actualiza los datos de un producto comercial existente.
     * Si se detecta un cambio en el medicamento asociado, se vuelven a sincronizar los atributos base de LINAME.
     *
     * @param \App\Models\Producto $producto Modelo del producto comercial a actualizar.
     * @param array $datos Nuevos datos provistos por el usuario.
     * @return \App\Models\Producto El producto actualizado.
     */
    public function actualizar(Producto $producto, array $datos): Producto
    {
        // Si cambió el medicamento asignado, actualizar la información base proveniente de LINAME
        if ($datos['medicamento_id'] != $producto->medicamento_id) {
            $medicamento = Medicamento::findOrFail($datos['medicamento_id']);
            $datos['nombre']             = $medicamento->nombre;
            $datos['forma_farmaceutica'] = $medicamento->forma_farmaceutica;
            $datos['concentracion']      = $medicamento->concentracion;
            $datos['precio_referencial'] = $medicamento->precio_referencial;
        }

        $producto->update($datos);
        return $producto;
    }

    /**
     * Elimina físicamente un producto comercial de la base de datos.
     *
     * @param \App\Models\Producto $producto Modelo del producto a eliminar.
     * @return void
     */
    public function eliminar(Producto $producto): void
    {
        $producto->delete();
    }

    /**
     * Busca y retorna un medicamento específico del catálogo por su ID.
     * Útil para respuestas AJAX o comprobaciones rápidas de datos.
     *
     * @param int $id ID del medicamento.
     * @return \App\Models\Medicamento|null
     */
    public function buscarMedicamento(int $id): ?Medicamento
    {
        return Medicamento::find($id);
    }
}