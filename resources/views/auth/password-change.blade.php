<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cambiar Contraseña - Andrufar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 font-sans antialiased flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full p-8 bg-white rounded-2xl shadow-2xl">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-black text-gray-800">Actualiza tu Seguridad</h2>
            <p class="text-gray-500 mt-2 text-sm">Tu administrador ha creado tu cuenta. Por seguridad, debes establecer una contraseña privada para continuar.</p>
        </div>

        @if (session('warning'))
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6">
                <p class="text-sm text-amber-700 font-medium">{{ session('warning') }}</p>
            </div>
        @endif

        <form action="{{ route('password.change.update') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nueva Contraseña</label>
                <input type="password" name="password" id="password" required 
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                    placeholder="Establece tu nueva clave">
                
                <div class="mt-3 space-y-2 bg-gray-50 p-3 rounded-lg border border-gray-100">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Requisitos de seguridad:</p>
                    <ul class="text-xs text-gray-500 space-y-2">
                        <li id="req-longitud" class="flex items-center gap-2 text-gray-500 transition-colors duration-150">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Mínimo 8 caracteres</span>
                        </li>
                        <li id="req-mayuscula" class="flex items-center gap-2 text-gray-500 transition-colors duration-150">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Al menos una mayúscula</span>
                        </li>
                        <li id="req-minuscula" class="flex items-center gap-2 text-gray-500 transition-colors duration-150">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Al menos una minúscula</span>
                        </li>
                        <li id="req-numero" class="flex items-center gap-2 text-gray-500 transition-colors duration-150">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Al menos un número</span>
                        </li>
                        <li id="req-especial" class="flex items-center gap-2 text-gray-500 transition-colors duration-150">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Al menos un carácter especial (@$!%*?&)</span>
                        </li>
                    </ul>
                </div>

                @error('password')
                    <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" required 
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                    placeholder="Repite tu contraseña">
            </div>

            <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:shadow-blue-200 transition-all transform hover:-translate-y-0.5 active:scale-95">
                Guardar y Entrar al Sistema
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-500 text-xs font-medium transition-colors">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
    <script>
        // Validación en tiempo real de contraseña
        document.getElementById('password').addEventListener('input', function() {
            const val = this.value;
            const requisitos = {
                'req-longitud':  val.length >= 8,
                'req-mayuscula': /[A-Z]/.test(val),
                'req-minuscula': /[a-z]/.test(val),
                'req-numero':    /\d/.test(val),
                'req-especial':  /[@$!%*?&]/.test(val),
            };

            for (const [id, cumple] of Object.entries(requisitos)) {
                const el = document.getElementById(id);
                if (el) {
                    const svg = el.querySelector('svg');
                    
                    if (cumple) {
                        el.classList.remove('text-gray-500');
                        el.classList.add('text-green-600');
                        svg.classList.remove('text-gray-400');
                        svg.classList.add('text-green-500');
                    } else {
                        el.classList.remove('text-green-600');
                        el.classList.add('text-gray-500');
                        svg.classList.remove('text-green-500');
                        svg.classList.add('text-gray-400');
                    }
                }
            }
        });
    </script>
</body>
</html>
