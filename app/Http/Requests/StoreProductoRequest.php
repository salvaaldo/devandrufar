<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo'         => ['required', 'string', 'max:50', 'unique:productos,codigo'],
            'medicamento_id' => ['required', 'exists:medicamentos,id'],
            'origen'         => ['nullable', 'string', 'max:100'],
            'marca'          => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required'         => 'El código es obligatorio.',
            'codigo.unique'           => 'Ya existe un producto con ese código.',
            'medicamento_id.required' => 'Debes seleccionar un medicamento.',
            'medicamento_id.exists'   => 'El medicamento seleccionado no existe.',
        ];
    }
}