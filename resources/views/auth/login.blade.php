<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Andrufar - Iniciar Sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #080f1e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        /* Fondo con puntos sutiles */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 1;
            display: flex;
            width: 100%;
            max-width: 860px;
            min-height: 520px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5), 0 0 0 0.5px rgba(255,255,255,0.06);
        }

        /* ── Panel izquierdo ── */
        .panel-left {
            flex: 1;
            background: linear-gradient(145deg, #0d1b35 0%, #102244 55%, #0c1a30 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .panel-left::before {
            content: '';
            position: absolute;
            width: 380px; height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,0.2) 0%, transparent 65%);
            top: -100px; left: -100px;
        }

        .panel-left::after {
            content: '';
            position: absolute;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,0.14) 0%, transparent 65%);
            bottom: -70px; right: -60px;
        }

        .brand-logo {
            width: 62px; height: 62px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.4rem;
            position: relative;
            z-index: 1;
            box-shadow: 0 0 36px rgba(37,99,235,0.45);
        }

        .brand-logo svg { width: 32px; height: 32px; fill: white; }

        .brand-name {
            font-family: 'Sora', sans-serif;
            font-size: 20px;
            font-weight: 600;
            color: #e8f0fe;
            letter-spacing: -0.4px;
            text-align: center;
            position: relative;
            z-index: 1;
            margin-bottom: 0.4rem;
        }

        .brand-sub {
            font-size: 11px;
            color: #5a7abf;
            text-align: center;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
            margin-bottom: 2.8rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            width: 100%;
            max-width: 210px;
            position: relative;
            z-index: 1;
        }

        .stat-card {
            background: rgba(255,255,255,0.04);
            border: 0.5px solid rgba(255,255,255,0.07);
            border-radius: 12px;
            padding: 14px 10px;
            text-align: center;
            backdrop-filter: blur(4px);
        }

        .stat-num {
            font-family: 'Sora', sans-serif;
            font-size: 20px;
            font-weight: 600;
            color: #60a5fa;
            line-height: 1;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 10px;
            color: #5a7abf;
            letter-spacing: 0.4px;
        }

        .panel-footer {
            position: absolute;
            bottom: 1.4rem;
            left: 0; right: 0;
            text-align: center;
            font-size: 10px;
            color: #2a3e62;
            letter-spacing: 0.5px;
            z-index: 1;
        }

        /* ── Panel derecho (formulario) ── */
        .panel-right {
            width: 380px;
            background: #111827;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2.8rem 2.2rem;
            border-left: 0.5px solid rgba(255,255,255,0.05);
        }

        .form-title {
            font-family: 'Sora', sans-serif;
            font-size: 22px;
            font-weight: 600;
            color: #f1f5f9;
            margin-bottom: 0.3rem;
        }

        .form-desc {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 2rem;
        }

        /* Alerta de error */
        .alert-error {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: rgba(239,68,68,0.08);
            border: 0.5px solid rgba(239,68,68,0.25);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 1.4rem;
        }

        .alert-error svg { width: 16px; height: 16px; fill: #f87171; flex-shrink: 0; margin-top: 1px; }
        .alert-error span { font-size: 13px; color: #f87171; }

        /* Campos */
        .field-group { margin-bottom: 1.1rem; }

        .field-label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            color: #9ca3af;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .field-wrap { position: relative; }

        .field-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            pointer-events: none;
        }

        .field-input {
            width: 100%;
            background: #1a2236;
            border: 0.5px solid #2a3650;
            border-radius: 10px;
            padding: 11px 42px 11px 40px;
            font-size: 14px;
            color: #f1f5f9;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            appearance: none;
        }

        .field-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.18);
        }

        .field-input::placeholder { color: #374151; }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            color: #4b5563;
            display: flex;
            align-items: center;
            transition: color 0.15s;
        }

        .toggle-password:hover { color: #9ca3af; }
        .toggle-password svg { width: 16px; height: 16px; }

        /* Botón submit */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
            font-weight: 500;
            color: white;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 24px rgba(37,99,235,0.38);
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            margin-top: 1.6rem;
            letter-spacing: 0.2px;
        }

        .btn-submit:hover {
            opacity: 0.93;
            transform: translateY(-1px);
            box-shadow: 0 10px 30px rgba(37,99,235,0.45);
        }

        .btn-submit:active { transform: translateY(0); }
        .btn-submit svg { width: 15px; height: 15px; fill: white; }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 1.8rem 0 1.2rem;
        }

        .divider-line { flex: 1; height: 0.5px; background: #1f2937; }
        .divider-text { font-size: 10px; color: #374151; letter-spacing: 1px; text-transform: uppercase; }

        /* Badge seguro */
        .badge-secure {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(37,99,235,0.09);
            border: 0.5px solid rgba(37,99,235,0.2);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            color: #60a5fa;
            margin: 0 auto;
        }

        .badge-secure svg { width: 10px; height: 10px; fill: #60a5fa; }

        .badge-wrap { display: flex; justify-content: center; }

        /* Responsive: ocultar panel izquierdo en pantallas pequeñas */
        @media (max-width: 640px) {
            .panel-left { display: none; }
            .panel-right { width: 100%; border-left: none; }
            .login-container { max-width: 420px; }
        }
    </style>
</head>
<body>

<div class="login-container">

    {{-- ── Panel izquierdo: branding ── --}}
    <div class="panel-left">

        <div class="brand-logo">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-7 3a1 1 0 011 1v3h3a1 1 0 010 2h-3v3a1 1 0 01-2 0v-3H8a1 1 0 010-2h3V7a1 1 0 011-1z"/>
            </svg>
        </div>

        <div class="brand-name">Importadora Andrufar</div>
        <div class="brand-sub">Control Farmacéutico</div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-num">2.4k</div>
                <div class="stat-label">Medicamentos</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">OCR</div>
                <div class="stat-label">Detección IA</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">Auto</div>
                <div class="stat-label">Vencimientos</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">3</div>
                <div class="stat-label">Tipos de precio</div>
            </div>
        </div>

        <div class="panel-footer">© {{ date('Y') }} Andrufar S.R.L. — Bolivia</div>
    </div>

    {{-- ── Panel derecho: formulario ── --}}
    <div class="panel-right">

        <p class="form-title">Bienvenido</p>
        <p class="form-desc">Ingresa tus credenciales para continuar</p>

        {{-- Errores de autenticación --}}
        @if($errors->any())
            <div class="alert-error">
                <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                <span>Correo o contraseña incorrectos. Verifica e intenta de nuevo.</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="field-group">
                <label class="field-label" for="email">Correo electrónico</label>
                <div class="field-wrap">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="#4b5563">
                        <path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="field-input"
                        placeholder="admin@andrufar.com"
                        required
                        autofocus
                    >
                </div>
            </div>

            {{-- Contraseña --}}
            <div class="field-group">
                <label class="field-label" for="password">Contraseña</label>
                <div class="field-wrap">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="#4b5563">
                        <path d="M18 8h-1V6A5 5 0 007 6v2H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V10a2 2 0 00-2-2zm-6 9a2 2 0 110-4 2 2 0 010 4zm3.1-9H8.9V6a3.1 3.1 0 016.2 0v2z"/>
                    </svg>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="field-input"
                        placeholder="••••••••"
                        required
                    >
                    <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Mostrar contraseña">
                        <svg id="ojo-abierto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg id="ojo-cerrado" class="hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Botón --}}
            <button type="submit" class="btn-submit">
                <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                Iniciar sesión
            </button>

        </form>

        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-text">Andrufar S.R.L.</span>
            <div class="divider-line"></div>
        </div>

        <div class="badge-wrap">
            <span class="badge-secure">
                <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                Acceso seguro
            </span>
        </div>

    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const ojoAbierto = document.getElementById('ojo-abierto');
        const ojoCerrado = document.getElementById('ojo-cerrado');

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
</script>

</body>
</html>