@extends('components.layouts.guest')

@section('titulo', 'Bienvenido a VisionGuard')

@section('contenido')
    <div class="flex-grow flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 relative">
        
        <div class="absolute top-6 left-6 flex items-center gap-2">
            <div class="bg-blue-600 text-white p-1.5 rounded-lg shadow-lg shadow-blue-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="font-bold text-xl tracking-tight text-slate-800 dark:text-white">
                Vision<span class="text-blue-600 dark:text-blue-400">Guard</span>
            </span>
        </div>

        <div class="text-center max-w-3xl mx-auto py-20">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold uppercase tracking-wider border border-blue-100 dark:border-blue-800 mb-6 animate-fade-in-down">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                Sistema v2.0 Activo
            </div>

            <h1 class="text-5xl sm:text-6xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-6 animate-fade-in-up">
                Seguridad Inteligente <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400">
                    Al Alcance de Todos
                </span>
            </h1>

            <p class="text-lg sm:text-xl text-slate-600 dark:text-slate-300 mb-10 leading-relaxed animate-fade-in-up delay-100">
                Monitorea tus espacios en tiempo real desde cualquier dispositivo. 
                Compatible con ESP32, cámaras IP y móviles.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up delay-200">
                @auth
                    {{-- Bloque para usuarios conectados --}}
                    @php
                        $role = Auth::user()->role?->name ?? 'user';
                        $route = match ($role) {
                            'admin' => 'admin.dashboard',
                            'supervisor' => 'supervisor.dashboard',
                            'mantenimiento' => 'mantenimiento.dashboard',
                            default => 'user.dashboard',
                        };
                    @endphp
                    <a href="{{ route($route) }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/30 transition-all hover:scale-105 flex items-center gap-2">
                        Ir al Panel
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </a>
                @else
                    {{-- Bloque para invitados --}}
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/30 transition-all hover:scale-105 flex items-center gap-2 w-full sm:w-auto justify-center">
                        Iniciar Sesión
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                    </a>
                @endauth
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-5xl mx-auto mt-12 w-full">
            <div class="p-6 bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm text-center">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                </div>
                <h3 class="font-bold text-slate-900 dark:text-white">Multi-Cámara</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Soporte para Video Wall y monitoreo simultáneo.</p>
            </div>
            <div class="p-6 bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm text-center">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="font-bold text-slate-900 dark:text-white">Alta Seguridad</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Roles estrictos y validación de conexiones.</p>
            </div>
            <div class="p-6 bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm text-center">
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <h3 class="font-bold text-slate-900 dark:text-white">Tiempo Real</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Detección de estado online/offline instantánea.</p>
            </div>
        </div>
    </div>

    <footer class="py-6 text-center text-sm text-slate-400 dark:text-slate-500">
        &copy; {{ date('Y') }} Proyecto de Programación Web Avanzada
    </footer>
@endsection