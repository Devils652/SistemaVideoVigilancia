@extends('components.layouts.app')

@section('titulo', 'Editar Cámara')

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
                Cancelar
            </a>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="h-1.5 w-full bg-amber-500"></div>

            <div class="p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="p-3 bg-amber-50 dark:bg-amber-900/30 rounded-xl text-amber-600 dark:text-amber-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Editar Configuración</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Modificando: {{ $camera->name }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route($prefix . 'cameras.update', $camera) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nombre</label>
                            <input type="text" name="name" value="{{ $camera->name }}" required 
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none text-slate-900 dark:text-white transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">URL / IP de Conexión</label>
                            <input type="text" name="ip" value="{{ $camera->ip }}" required 
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none text-slate-900 dark:text-white font-mono text-sm transition-all">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Estado</label>
                                <select name="status" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                                    <option value="1" {{ $camera->status ? 'selected' : '' }}>🟢 Activa</option>
                                    <option value="0" {{ !$camera->status ? 'selected' : '' }}>🔴 Inactiva</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Ubicación</label>
                                <input type="text" name="location" value="{{ $camera->location }}" 
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Grupo Asignado</label>
                            <div class="relative">
                                <select name="group" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 appearance-none">
                                    <option value="">Sin Grupo</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->name }}" {{ $camera->group == $group->name ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-4">
                            <button class="flex-1 py-3.5 px-4 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transform transition hover:-translate-y-0.5">
                                Guardar Cambios
                            </button>
                            
                            @if($userRole === 'admin')
                                <button type="button" onclick="if(confirm('¿Borrar cámara permanentemente?')) document.getElementById('delete-form').submit();" class="px-4 py-3.5 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
                
                @if($userRole === 'admin')
                    <form id="delete-form" action="{{ route($prefix . 'cameras.destroy', $camera) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection