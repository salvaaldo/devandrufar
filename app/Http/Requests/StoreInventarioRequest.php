<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'producto_id'       => ['required', 'exists:productos,id'],
            'lote'              => ['required', 'string', 'max:100'],
            'cantidad'          => ['required', 'integer', 'min:1'],
            'fecha_vencimiento' => ['required', 'date'],
            'fecha_ingreso'     => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'producto_id.required'       => 'Debes seleccionar un producto.',
            'producto_id.exists'         => 'El producto seleccionado no existe.',
            'lote.required'              => 'El número de lote es obligatorio.',
            'cantidad.required'          => 'La cantidad es obligatoria.',
            'cantidad.min'               => 'La cantidad debe ser mayor a 0.',
            'fecha_vencimiento.required' => 'La fecha de vencimiento es obligatoria.',
            'fecha_ingreso.required'     => 'La fecha de ingreso es obligatoria.',
        ];
    }
}