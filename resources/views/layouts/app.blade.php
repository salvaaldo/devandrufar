<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Andrufar') }} - @yield('title', 'Sistema')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-50">

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-gray-900">

            <!-- Logo -->
            <div><img src="../images/logo.jpeg" alt="logo" style="width: 50%; height: auto;"></div>
            <div class="flex items-center ps-2 mb-6 mt-2">
                <span class="self-center text-xl font-bold whitespace-nowrap text-white">Andrufar </span>
            </div>

            <ul class="space-y-2 font-medium">

                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <!-- Usuarios (solo admin) -->
                @if (auth()->user()->esAdmin())
                    <li>
                        <a href="{{ route('usuarios.index') }}"
                            class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group {{ request()->routeIs('usuarios.*') ? 'bg-gray-700' : '' }}">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            <span class="ms-3">Usuarios</span>
                        </a>
                    </li>
                @endif

                <!-- Visión Artificial (OCR) -->
                <li>
                    <a href="{{ route('ocr.index') }}"
                        class="flex items-center p-2 rounded-lg text-white bg-blue-900/30 border border-blue-500/20 hover:bg-blue-800/40 group {{ request()->routeIs('ocr.index') ? 'bg-blue-800 shadow-lg' : '' }} transition-all duration-200">
                        <svg class="w-5 h-5 text-blue-400 group-hover:text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="ms-3 font-bold">Detección OCR</span>
                    </a>
                </li>

                <!-- Historial OCR -->
                <li>
                    <a href="{{ route('ocr.historial') }}"
                        class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group {{ request()->routeIs('ocr.historial') ? 'bg-gray-700' : '' }}">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ms-3">Historial OCR</span>
                    </a>
                </li>

            </ul>

            <!-- Usuario autenticado -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-700">
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()->rol) }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left flex items-center p-2 rounded-lg text-gray-400 hover:bg-gray-700 hover:text-white text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Cerrar sesión
                    </button>
                </form>
            </div>
                <!-- Gestión Administrativa (Solo Admin) -->
                @if (auth()->user()->esAdmin())
                    <!-- Clientes -->
                    <li>
                        <a href="{{ route('clientes.index') }}"
                            class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group {{ request()->routeIs('clientes.*') ? 'bg-gray-700' : '' }}">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z" />
                            </svg>
                            <span class="ms-3">Clientes</span>
                        </a>
                    </li>
                    <!-- Medicamentos -->
                    <li>
                        <a href="{{ route('medicamentos.index') }}"
                            class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group {{ request()->routeIs('medicamentos.*') ? 'bg-gray-700' : '' }}">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 3a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3zm1 2v1h1V5h-1zM11 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3zm2 2v-1h1v1h-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ms-3">Medicamentos</span>
                        </a>
                    </li>
                    <!-- Productos -->
                    <li>
                        <a href="{{ route('productos.index') }}"
                            class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group {{ request()->routeIs('productos.*') ? 'bg-gray-700' : '' }}">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                <path fill-rule="evenodd"
                                    d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ms-3">Productos</span>
                        </a>
                    </li>
                @endif
            <!-- Inventario -->
            <li>
                <a href="{{ route('inventario.index') }}"
                    class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group {{ request()->routeIs('inventario.*') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                    </svg>
                    <span class="ms-3">Lista de productos de empresa</span>
                </a>
            </li>

            @if (auth()->user()->esAdmin())
                {{-- cotizaciones --}}
                <a href="{{ route('cotizaciones.index') }}"
                    class="{{ request()->routeIs('cotizaciones.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }} flex items-center gap-3 px-4 py-2.5 rounded-lg transition text-sm font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
                    </svg>
                    Cotizaciones
                </a>
            @endif
            <!-- Alertas -->
            <li>
                <a href="{{ route('alertas.index') }}"
                    class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group {{ request()->routeIs('alertas.*') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="ms-3">Alertas</span>
                </a>
            </li>

            <hr class="my-4 border-gray-700">
            <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Auditoría</p>

            <!-- Bajas de Medicamentos -->
            <li>
                <a href="{{ route('historial-bajas.index') }}"
                    class="flex items-center p-2 rounded-lg text-white hover:bg-red-600 group {{ request()->routeIs('historial-bajas.index') ? 'bg-red-600 shadow-lg' : '' }} transition-all duration-200">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="ms-3">Bajas de Medicamentos</span>
                </a>
            </li>

            @if (auth()->user()->esAdmin())
                <!-- Reportes de Ventas -->
                <li>
                    <a href="{{ route('reportes.ventas') }}"
                        class="flex items-center p-2 rounded-lg text-white hover:bg-indigo-600 group {{ request()->routeIs('reportes.ventas') ? 'bg-indigo-600 shadow-lg' : '' }} transition-all duration-200">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="ms-3">Reporte de Ventas</span>
                    </a>
                </li>
            @endif

        </div>
    </aside>

    <!-- Contenido principal -->
    <div class="sm:ml-64 min-h-screen flex flex-col">

        <!-- Banner de Alerta Global -->
        @php
            $vencidosCount = $globalVencidosCount ?? 0;
            $proximosCount = $globalProximosCount ?? 0;
        @endphp

        @if($vencidosCount > 0 || $proximosCount > 0)
            @php
                $hasVencidos = $vencidosCount > 0;
                $bgColor = $hasVencidos ? 'bg-red-600' : 'bg-orange-500';
                $btnColor = $hasVencidos ? 'text-red-600' : 'text-orange-600';
            @endphp
            <div id="voice-alert-banner" class="{{ $bgColor }} text-white px-6 py-2 flex items-center justify-between shadow-lg z-50 animate-pulse">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <span class="font-bold tracking-wide">
                        @if($hasVencidos)
                            ATENCIÓN: Tienes {{ $vencidosCount }} medicamentos vencidos que requieren acción inmediata.
                        @else
                            AVISO: Tienes {{ $proximosCount }} medicamentos por vencer pronto.
                        @endif
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="repetirVoz()" class="bg-white/20 hover:bg-white/30 text-white p-1.5 rounded-full transition-colors mr-2" title="Repetir Alerta de Voz">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 14.828a1 1 0 01-1.414-1.414 4 4 0 000-5.656 1 1 0 011.414-1.414 6 6 0 010 8.486z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <a href="{{ route('alertas.index') }}" class="bg-white {{ $btnColor }} px-4 py-1 rounded-full text-xs font-black uppercase hover:bg-gray-100 transition-colors">
                        Ver Detalles
                    </a>
                    <button onclick="document.getElementById('voice-alert-banner').remove()" class="text-white/80 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Si acabamos de loguearnos, forzamos el reinicio de la alerta
                    @if(session('login_success'))
                        sessionStorage.removeItem('voice_alert_played');
                        console.log("🔐 Login detectado: Reiniciando alerta de voz.");
                    @endif

                    const getBestVoice = () => {
                        const voices = window.speechSynthesis.getVoices();
                        // 1. Buscar voces de Google (suelen ser más naturales)
                        let voice = voices.find(v => v.lang.includes('es') && v.name.includes('Google'));
                        // 2. Buscar voces "Neural" o de Microsoft que suelen ser buenas
                        if (!voice) voice = voices.find(v => v.lang.includes('es') && (v.name.includes('Neural') || v.name.includes('Helena')));
                        // 3. Cualquier voz en español
                        if (!voice) voice = voices.find(v => v.lang.includes('es'));
                        return voice;
                    };

                    const speakAlert = () => {
                        if (sessionStorage.getItem('voice_alert_played')) return;

                        const vencidos = {{ $vencidosCount }};
                        const proximos = {{ $proximosCount }};
                        
                        if (vencidos === 0 && proximos === 0) return;

                        let texto = "";
                        if (vencidos > 0) {
                            texto += "Atención. Tiene medicamentos vencidos. ";
                        }
                        if (proximos > 0) {
                            texto += "Aviso. Tiene medicamentos por vencer. ";
                        }

                        console.log("📢 Intentando alerta de voz: " + texto);
                        
                        window.speechSynthesis.cancel(); 
                        
                        const message = new SpeechSynthesisUtterance(texto);
                        
                        // Configuración para voz más natural
                        const selectedVoice = getBestVoice();
                        if (selectedVoice) {
                            message.voice = selectedVoice;
                            console.log("🎙️ Voz seleccionada: " + selectedVoice.name);
                        }

                        message.lang = 'es-ES';
                        message.rate = 0.95; // Un poco más lento suele sonar mejor
                        message.pitch = 1.0;
                        message.volume = 1.0;

                        message.onstart = () => {
                            console.log("✅ La voz ha iniciado correctamente.");
                            sessionStorage.setItem('voice_alert_played', 'true');
                        };

                        message.onerror = (event) => {
                            console.error("❌ Error en SpeechSynthesis:", event.error);
                        };
                        
                        window.speechSynthesis.speak(message);
                    };

                    // Las voces se cargan de forma asíncrona en algunos navegadores
                    if (window.speechSynthesis.onvoiceschanged !== undefined) {
                        window.speechSynthesis.onvoiceschanged = () => {
                            console.log("🔊 Voces cargadas. Disponibles: " + window.speechSynthesis.getVoices().length);
                        };
                    }

                    // Intento 1: Automático tras 1.5s
                    setTimeout(speakAlert, 1500);

                    // Intento 2: Al primer clic
                    document.addEventListener('click', function() {
                        if (!sessionStorage.getItem('voice_alert_played')) {
                            speakAlert();
                        }
                    }, { once: true });
                });

                function repetirVoz() {
                    sessionStorage.removeItem('voice_alert_played');
                    location.reload();
                }
            </script>
        @endif

        <!-- Topbar -->
        <nav class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between">
            <button data-drawer-target="sidebar" data-drawer-toggle="sidebar"
                class="sm:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1 class="text-lg font-semibold text-gray-700">@yield('title', 'Dashboard')</h1>
            <span class="text-sm text-gray-400">{{ now()->format('d/m/Y') }}</span>
        </nav>

        <!-- Alertas -->
        <div class="px-6 pt-4">
            @if (session('success'))
                <div class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 border border-green-200"
                    role="alert">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 border border-red-200"
                    role="alert">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Página -->
        <main class="px-6 py-4">
            @yield('content')
        </main>

    </div>
    @stack('scripts')

</body>

</html>
