@extends('layouts.app')

@section('title', 'Nuevo Usuario')

@section('content')

<!-- Breadcrumb -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <a href="{{ route('usuarios.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                    Usuarios
                </a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Nuevo Usuario</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Crear Nuevo Usuario</h2>
            <p class="text-gray-500 text-sm mt-1">Complete el formulario para registrar un nuevo usuario en el sistema</p>
        </div>
        <a href="{{ route('usuarios.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Cancelar
        </a>
    </div>
</div>

<!-- Formulario -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Formulario Principal -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            
            <!-- Header del formulario -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 rounded-lg p-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">Información del Usuario</h3>
                        <p class="text-blue-100 text-xs">Campos marcados con * son obligatorios</p>
                    </div>
                </div>
            </div>

            <!-- Contenido del formulario -->
            <form action="{{ route('usuarios.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">

                    <!-- Información Personal -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Datos Personales
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <!-- Nombre -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">
                                    Nombre completo <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name') }}" 
                                        placeholder="Ej: Juan Pérez García"
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 @error('name') border-red-500 ring-2 ring-red-500 @enderror">
                                </div>
                                @error('name')
                                    <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- CI -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">
                                    Cédula de Identidad <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="ci" value="{{ old('ci') }}" 
                                        placeholder="Ej: 12345678"
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 @error('ci') border-red-500 ring-2 ring-red-500 @enderror">
                                </div>
                                @error('ci')
                                    <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">
                                    Teléfono
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="telefono" value="{{ old('telefono') }}" 
                                        placeholder="Ej: 77123456"
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3">
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">
                                    Correo electrónico <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                    </div>
                                    <input type="email" name="email" value="{{ old('email') }}" 
                                        placeholder="Ej: usuario@ejemplo.com"
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 @error('email') border-red-500 ring-2 ring-red-500 @enderror">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Seguridad -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Credenciales de Acceso
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <!-- Contraseña -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">
                                    Contraseña <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <input type="password" name="password" id="password"
                                        placeholder="Ingrese contraseña segura"
                                        class="pl-10 pr-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 @error('password') border-red-500 ring-2 ring-red-500 @enderror">
                                    <button type="button" onclick="togglePassword('password', 'ojo1-abierto', 'ojo1-cerrado')"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                        <svg id="ojo1-abierto" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg id="ojo1-cerrado" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">
                                    Confirmar contraseña <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        placeholder="Confirme la contraseña"
                                        class="pl-10 pr-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3">
                                    <button type="button" onclick="togglePassword('password_confirmation', 'ojo2-abierto', 'ojo2-cerrado')"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                        <svg id="ojo2-abierto" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg id="ojo2-cerrado" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <!-- Indicador de Fortaleza de Contraseña -->
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-xs font-semibold text-gray-700 mb-3">Requisitos de seguridad:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div id="req-longitud" class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Mínimo 8 caracteres</span>
                                </div>
                                <div id="req-mayuscula" class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Al menos una mayúscula</span>
                                </div>
                                <div id="req-minuscula" class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Al menos una minúscula</span>
                                </div>
                                <div id="req-numero" class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Al menos un número</span>
                                </div>
                                <div id="req-especial" class="flex items-center gap-2 text-xs text-gray-500 md:col-span-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Al menos un carácter especial (@$!%*?&)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Rol -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                            Permisos del Sistema
                        </h4>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">
                                Rol de usuario <span class="text-red-500">*</span>
                            </label>
                            <select name="rol" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 @error('rol') border-red-500 ring-2 ring-red-500 @enderror">
                                <option value="">Seleccionar rol</option>
                                <option value="admin" {{ old('rol') === 'admin' ? 'selected' : '' }}>
                                    Administrador - Acceso completo al sistema
                                </option>
                                <option value="operador" {{ old('rol') === 'operador' ? 'selected' : '' }}>
                                    Operador - Acceso limitado
                                </option>
                            </select>
                            @error('rol')
                                <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('usuarios.index') }}" 
                        class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium text-sm">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition font-medium text-sm shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Usuario
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Panel de Ayuda -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="bg-blue-100 rounded-lg p-2">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-800">Información de Ayuda</h3>
            </div>

            <div class="space-y-4">
                <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                    <h4 class="font-semibold text-sm text-blue-900 mb-2">Roles del Sistema</h4>
                    <ul class="space-y-2 text-xs text-blue-800">
                        <li class="flex items-start gap-2">
                            <span class="text-blue-500">•</span>
                            <span><strong>Administrador:</strong> Acceso completo al sistema, gestión de usuarios y configuraciones</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-500">•</span>
                            <span><strong>Operador:</strong> Acceso limitado a operaciones básicas del inventario</span>
                        </li>
                    </ul>
                </div>

                <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                    <h4 class="font-semibold text-sm text-yellow-900 mb-2">Seguridad de Contraseña</h4>
                    <p class="text-xs text-yellow-800">
                        Una contraseña segura protege la cuenta del usuario. Asegúrese de que cumpla con todos los requisitos de seguridad.
                    </p>
                </div>

                <div class="p-3 bg-green-50 rounded-lg border border-green-100">
                    <h4 class="font-semibold text-sm text-green-900 mb-2">💡 Consejo</h4>
                    <p class="text-xs text-green-800">
                        El usuario recibirá sus credenciales por correo electrónico. Verifique que el email sea correcto antes de guardar.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function togglePassword(inputId, ojoAbiertoId, ojoCerradoId) {
        const input = document.getElementById(inputId);
        const ojoAbierto = document.getElementById(ojoAbiertoId);
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
            const svg = el.querySelector('svg');
            const span = el.querySelector('span');
            
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
    });
</script>
@endpush