<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo'    => ['required', 'string', 'max:20', 'unique:clientes,codigo'],
            'nombre'    => ['required', 'string', 'max:255'],
            'nit'       => ['nullable', 'string', 'max:20'],
            'telefono'  => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique'   => 'Ya existe un cliente con ese código.',
            'nombre.required' => 'El nombre es obligatorio.',
        ];
    }
}