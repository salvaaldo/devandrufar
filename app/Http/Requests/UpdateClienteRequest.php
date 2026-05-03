<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente')->id;

        return [
            'codigo'    => ['required', 'string', 'max:20', 'unique:clientes,codigo,' . $clienteId],
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