@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-700">Editar Usuario</h2>
            <a href="{{ route('usuarios.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                ← Volver
            </a>
        </div>

        <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Nombre -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Nombre completo</label>
                    <input type="text" name="name" value="{{ old('name', $usuario->name) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CI -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Cédula de Identidad</label>
                    <input type="text" name="ci" value="{{ old('ci', $usuario->ci) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('ci') border-red-500 @enderror">
                    @error('ci')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Contraseña</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 @error('password') border-red-500 @enderror">
                        <button type="button" onclick="togglePassword('password', 'ojo1-abierto', 'ojo1-cerrado')"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                            <svg id="ojo1-abierto" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="ojo1-cerrado" class="w-4 h-4 hidden" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <!-- Requisitos -->
                    <ul class="mt-2 text-xs space-y-1" id="requisitos">
                        <li id="req-longitud" class="text-red-500">✗ Mínimo 8 caracteres</li>
                        <li id="req-mayuscula" class="text-red-500">✗ Al menos una mayúscula</li>
                        <li id="req-minuscula" class="text-red-500">✗ Al menos una minúscula</li>
                        <li id="req-numero" class="text-red-500">✗ Al menos un número</li>
                        <li id="req-especial" class="text-red-500">✗ Al menos un carácter especial (@$!%*?&)</li>
                    </ul>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Confirmar contraseña</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10">
                        <button type="button"
                            onclick="togglePassword('password_confirmation', 'ojo2-abierto', 'ojo2-cerrado')"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                            <svg id="ojo2-abierto" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="ojo2-cerrado" class="w-4 h-4 hidden" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Rol -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Rol</label>
                    <select name="rol"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('rol') border-red-500 @enderror">
                        <option value="admin" {{ old('rol', $usuario->rol) === 'admin' ? 'selected' : '' }}>Admin
                        </option>
                        <option value="operador" {{ old('rol', $usuario->rol) === 'operador' ? 'selected' : '' }}>Operador
                        </option>
                    </select>
                    @error('rol')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Estado</label>
                    <select name="activo"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="1" {{ old('activo', $usuario->activo) ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ !old('activo', $usuario->activo) ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-6 py-2.5">
                    Actualizar Usuario
                </button>
            </div>

        </form>
    </div>
@endsection
@push('scripts')
    <script>
        function togglePassword(inputId, ojoAbiertoid, ojoCerradoId) {
            const input = document.getElementById(inputId);
            const ojoAbierto = document.getElementById(ojoAbiertoid);
            const ojoCerrado = document.getElementById(ojoCerradoId);
            if (input.type === 'password') {
                input.type = 'text';
                ojoAbierto.classList.add('hidden');
                ojoCerrado.classList.remove('hidden');
            } else {
                input.type = 'password';
                ojoAbierto.classList.remove('hidden');
                ojoCerrado.classList.add('hidden');
            }
        }

        document.getElementById('password').addEventListener('input', function() {
            const val = this.value;
            const requisitos = {
                'req-longitud': val.length >= 8,
                'req-mayuscula': /[A-Z]/.test(val),
                'req-minuscula': /[a-z]/.test(val),
                'req-numero': /\d/.test(val),
                'req-especial': /[@$!%*?&]/.test(val),
            };
            for (const [id, cumple] of Object.entries(requisitos)) {
                const el = document.getElementById(id);
                if (cumple) {
                    el.classList.remove('text-red-500');
                    el.classList.add('text-green-500');
                    el.textContent = '✓ ' + el.textContent.substring(2);
                } else {
                    el.classList.remove('text-green-500');
                    el.classList.add('text-red-500');
                    el.textContent = '✗ ' + el.textContent.substring(2);
                }
            }
        });
    </script>
@endpush
