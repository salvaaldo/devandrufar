<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'ci'       => ['required', 'string', 'max:20', 'unique:users,ci'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'],
            'rol'      => ['required', 'in:admin,operador'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'El nombre es obligatorio.',
            'ci.required'        => 'La CI es obligatoria.',
            'ci.unique'          => 'Ya existe un usuario con esa CI.',
            'email.required'     => 'El correo es obligatorio.',
            'email.unique'       => 'Ya existe un usuario con ese correo.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.regex' => 'La contraseña debe tener al menos una mayúscula, una minúscula, un número y un carácter especial (@$!%*?&).',
            'password.min'   => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'rol.required'       => 'El rol es obligatorio.',
            'rol.in'             => 'El rol debe ser admin u operador.',
        ];
    }
}
