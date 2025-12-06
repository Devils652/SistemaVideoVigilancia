@extends('components.layouts.app')

@section('titulo', 'Nueva Cámara')

@section('contenido')

    @php
        $userRole = Auth::user()->role->name ?? 'user';
        $prefix = match ($userRole) {
            'admin' => 'admin.',
            'supervisor' => 'supervisor.',
            'mantenimiento' => 'mantenimiento.',
            default => 'user.',
        };
    @endphp

    <div class="max-w-2xl mx-auto">
        
        <div class="mb-6">
            <a href="{{ route($prefix . 'cameras.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Volver al panel
            </a>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 to-indigo-600"></div>

            <div class="p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Registrar Dispositivo</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Configura una nueva fuente de video.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route($prefix . 'cameras.store') }}">
                    @csrf

                    <div class="space-y-6">
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nombre descriptivo</label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border @error('name') border-red-500 dark:border-red-500 @else border-slate-200 dark:border-slate-700 @enderror focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-900 dark:text-white placeholder-slate-400 transition-all" 
                                placeholder="Ej: Puerta Principal">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-slate-700">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Tipo de Conexión</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="camera_type" value="phone" class="peer sr-only" checked onchange="updatePlaceholder('phone')">
                                    <div class="p-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500 group-hover:border-slate-300 dark:group-hover:border-slate-500 transition-all text-center">
                                        <span class="text-2xl block mb-1">📱</span>
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Celular IP</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="camera_type" value="esp32" class="peer sr-only" onchange="updatePlaceholder('esp32')">
                                    <div class="p-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500 group-hover:border-slate-300 dark:group-hover:border-slate-500 transition-all text-center">
                                        <span class="text-2xl block mb-1">🤖</span>
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-200">ESP32</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="camera_type" value="youtube" class="peer sr-only" onchange="updatePlaceholder('youtube')">
                                    <div class="p-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500 group-hover:border-slate-300 dark:group-hover:border-slate-500 transition-all text-center">
                                        <span class="text-2xl block mb-1">🔴</span>
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-200">YouTube</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label id="inputLabel" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">URL o IP</label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="ip" 
                                    id="ipInput"
                                    value="{{ old('ip') }}"
                                    required 
                                    class="w-full pl-4 pr-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border @error('ip') border-red-500 dark:border-red-500 @else border-slate-200 dark:border-slate-700 @enderror focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-900 dark:text-white font-mono text-sm placeholder-slate-400 transition-all" 
                                    placeholder="http://192.168.1.x:8080/video"
                                >
                            </div>
                            <p id="helperText" class="text-xs text-slate-500 dark:text-slate-400 mt-2 ml-1">Pegue la dirección completa.</p>
                            @error('ip')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Estado Inicial</label>
                                <select name="status" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                    <option value="1">🟢 Activa</option>
                                    <option value="0">🔴 Inactiva</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Ubicación</label>
                                <input type="text" name="location" value="{{ old('location') }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="Ej: Recepción">
                            </div>
                        </div>
                        
<div>
    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Grupo</label>
    <div class="flex gap-2">
        <select name="group" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
            <option value="" selected>Sin grupo asignado</option>
            @foreach(\App\Models\CameraGroup::all() as $g)
                <option value="{{ $g->name }}">{{ $g->name }}</option>
            @endforeach
        </select>
    </div>
    <p class="text-xs text-slate-500 mt-1">Si no ves el grupo, créalo desde el panel principal.</p>
</div>

                        <div class="pt-4">
                            <button class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transform transition hover:-translate-y-0.5 active:translate-y-0">
                                Confirmar y Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updatePlaceholder(type) {
            const input = document.getElementById('ipInput');
            const label = document.getElementById('inputLabel');
            const helper = document.getElementById('helperText');

            if (type === 'phone') {
                label.innerText = 'URL del Celular (IP Webcam)';
                input.placeholder = 'http://192.168.1.X:8080/video';
                helper.innerText = 'Use la dirección completa terminada en /video';
            } else if (type === 'esp32') {
                label.innerText = 'IP Local (ESP32)';
                input.placeholder = '192.168.1.X';
                helper.innerText = 'Solo la IP numérica. Se agregará el puerto automáticamente.';
            } else if (type === 'youtube') {
                label.innerText = 'Enlace de Video';
                input.placeholder = 'https://www.youtube.com/watch?v=...';
                helper.innerText = 'Enlace directo del video o directo de YouTube.';
            }
        }
        updatePlaceholder('phone');
    </script>

@endsection