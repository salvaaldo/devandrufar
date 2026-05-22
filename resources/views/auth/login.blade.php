<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Andrufar - Iniciar Sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        
        .input-glow:focus {
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.2);
            border-color: rgba(56, 189, 248, 0.5);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex items-center justify-center overflow-hidden relative selection:bg-cyan-500/30">

    <!-- Background decoration for smaller screens -->
    <div class="absolute top-0 -left-4 w-72 h-72 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob lg:hidden"></div>
    <div class="absolute top-0 -right-4 w-72 h-72 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000 lg:hidden"></div>

    <div class="w-full h-screen lg:flex lg:p-4 gap-4 relative z-10 max-w-[1600px] mx-auto">
        
        {{-- ── Panel Izquierdo (Imagen) ── --}}
        <div class="hidden lg:flex lg:w-3/5 lg:relative rounded-3xl overflow-hidden shadow-2xl relative group">
            <!-- Imagen generada por IA -->
            <img src="{{ asset('img/login_bg.png') }}" alt="AI Pharmacy Vision" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
            
            <!-- Overlay gradiente -->
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/40 to-transparent mix-blend-overlay"></div>
            
            <!-- Contenido sobre la imagen -->
            <div class="relative z-10 flex flex-col justify-between h-full p-12 text-white w-full">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center shadow-[0_0_20px_rgba(34,211,238,0.4)]">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">Andrufar <span class="text-cyan-400 font-light">S.R.L.</span></span>
                </div>

                <div class="max-w-xl pb-10">
                    <div class="inline-block px-4 py-1.5 rounded-full border border-cyan-500/30 bg-cyan-500/10 backdrop-blur-md mb-6">
                        <span class="text-xs font-semibold text-cyan-300 uppercase tracking-widest">Sistema Web Inteligente</span>
                    </div>
                    <h1 class="text-5xl font-extrabold leading-tight mb-4 tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">
                        Control Farmacéutico <br>con <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Visión Artificial</span>
                    </h1>
                    <p class="text-gray-300 text-lg font-light leading-relaxed mb-10 max-w-lg">
                        Detección automática de fechas de caducidad y gestión de productos PEPS potenciado por inteligencia artificial.
                    </p>
                    
                    <!-- Stats / Features -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="glass-panel p-4 rounded-2xl border border-cyan-500/20 shadow-[0_4px_20px_rgba(34,211,238,0.1)] transition-transform hover:-translate-y-1">
                            <svg class="w-6 h-6 text-cyan-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <div class="text-white font-semibold">Precisión OCR</div>
                            <div class="text-cyan-200/60 text-xs mt-1">Lectura inteligente de lotes en segundos.</div>
                        </div>
                        <div class="glass-panel p-4 rounded-2xl border border-blue-500/20 shadow-[0_4px_20px_rgba(59,130,246,0.1)] transition-transform hover:-translate-y-1">
                            <svg class="w-6 h-6 text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <div class="text-white font-semibold">Gestión Ágil</div>
                            <div class="text-blue-200/60 text-xs mt-1">Alertas automáticas de vencimiento.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Panel Derecho (Formulario) ── --}}
        <div class="w-full lg:w-2/5 flex flex-col justify-center items-center p-6 sm:p-12 h-full lg:h-auto">
            
            <div class="w-full max-w-md lg:px-8">
                <!-- Logo para mobile -->
                <div class="lg:hidden flex items-center justify-center gap-3 mb-10">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center shadow-[0_0_20px_rgba(34,211,238,0.3)]">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold">Andrufar</span>
                </div>

                <div class="text-center lg:text-left mb-10">
                    <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Bienvenido de nuevo</h2>
                    <p class="text-gray-400 text-sm">Ingresa a tu cuenta para gestionar los productos de empresa</p>
                </div>

                @if($errors->any())
                    <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-4 mb-8 flex items-start gap-3 animate-pulse">
                        <svg class="w-5 h-5 text-red-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-red-300">Correo o contraseña incorrectos. Verifica tus credenciales e intenta de nuevo.</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email Input -->
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-gray-300 ml-1">Correo Electrónico</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 group-focus-within:text-cyan-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="input-glow w-full bg-gray-800/50 border border-gray-700 text-white text-sm rounded-2xl focus:ring-0 focus:border-cyan-500 block pl-11 p-3.5 transition-all placeholder-gray-500" placeholder="admin@andrufar.com">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between ml-1">
                            <label for="password" class="text-sm font-medium text-gray-300">Contraseña</label>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 group-focus-within:text-cyan-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input id="password" type="password" name="password" required
                                class="input-glow w-full bg-gray-800/50 border border-gray-700 text-white text-sm rounded-2xl focus:ring-0 focus:border-cyan-500 block pl-11 pr-11 p-3.5 transition-all placeholder-gray-500" placeholder="••••••••">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-white transition-colors">
                                <svg id="eye-open" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Botón de Login -->
                    <button type="submit" class="w-full relative group overflow-hidden rounded-2xl p-[1px] mt-6 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all">
                        <span class="absolute inset-0 bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-500 rounded-2xl opacity-80 group-hover:opacity-100 transition-opacity duration-300"></span>
                        <div class="relative bg-gray-900 bg-opacity-40 backdrop-blur-sm px-8 py-3.5 rounded-2xl flex items-center justify-center gap-2 transition-all duration-300 group-hover:bg-opacity-0">
                            <span class="text-white font-semibold text-sm tracking-wide">Acceder al Sistema</span>
                            <svg class="w-4 h-4 text-white transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </button>
                </form>

                <div class="mt-12 text-center">
                    <p class="text-xs text-gray-500 flex items-center justify-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Conexión cifrada y segura
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>