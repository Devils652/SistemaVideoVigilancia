@extends('components.layouts.app')

@section('titulo', 'Panel de Control')

@section('contenido')

@php
    // Lógica de rutas dinámica
    $prefix = Request::is('admin*') ? 'admin.' : (Request::is('supervisor*') ? 'supervisor.' : (Request::is('mantenimiento*') ? 'mantenimiento.' : 'user.'));
@endphp

<div x-data="{ showGroupModal: false }">

    <div class="flex flex-col sm:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Mis Dispositivos</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Organización por zonas.</p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route($prefix . 'cameras.multiview') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-sm font-bold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z" /></svg>
                Video Wall
            </a>

            @can('crear_camaras')
                <button @click="showGroupModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-sm font-bold rounded-xl hover:bg-indigo-200 dark:hover:bg-indigo-900/50 transition-all border border-indigo-200 dark:border-indigo-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Crear Grupo
                </button>

                <a href="{{ route($prefix . 'cameras.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/25 transition-all hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Nueva Cámara
                </a>
            @endcan
        </div>
    </div>

    @if($groupedCameras->isNotEmpty())
        
        @foreach($groupedCameras as $groupName => $cameras)
            
            <div class="relative flex py-5 items-center animate-fade-in">
                <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                <span class="flex-shrink-0 mx-4 text-slate-400 dark:text-slate-500 text-xs uppercase tracking-widest font-bold bg-slate-50 dark:bg-slate-950 px-2 border border-slate-200 dark:border-slate-800 rounded-full">
                    {{ $groupName }}
                </span>
                <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($cameras as $camera)
                    <div class="group relative bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-black/50 transition-all duration-300 border border-slate-200 dark:border-slate-700 overflow-hidden">
                        
                        <div class="h-40 bg-slate-100 dark:bg-slate-900 relative flex items-center justify-center overflow-hidden">
                            @if($camera->status)
                                <div class="absolute inset-0 bg-emerald-500/5 flex items-center justify-center">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-20"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                </div>
                                <span class="absolute top-3 right-3 px-2 py-0.5 bg-white/90 dark:bg-slate-900/90 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold rounded shadow-sm">ON</span>
                            @else
                                <div class="flex flex-col items-center text-slate-300 dark:text-slate-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                    <span class="text-[10px] font-bold uppercase">Off</span>
                                </div>
                            @endif

                            <a href="{{ route($prefix . 'cameras.show', $camera) }}" class="absolute inset-0 z-10"></a>
                        </div>

                        <div class="p-4">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ $camera->name }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $camera->location ?? 'Sin ubicación' }}</p>
                            
                            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700 flex gap-2">
                                <a href="{{ route($prefix . 'cameras.show', $camera) }}" class="flex-1 text-center py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-xs font-bold rounded hover:bg-blue-100 transition-colors">Ver</a>
                                @can('editar_camaras')
                                <a href="{{ route($prefix . 'cameras.edit', $camera) }}" class="flex-1 text-center py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded hover:bg-slate-200 transition-colors">Editar</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @endforeach

    @else
        <div class="flex flex-col items-center justify-center py-24 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 text-center">
            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-full mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Sin cámaras</h3>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 mb-6">Crea un grupo y agrega dispositivos.</p>
        </div>
    @endif


    <div x-show="showGroupModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showGroupModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showGroupModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-200 dark:border-slate-700">
                
                <form action="{{ route($prefix . 'cameras.group.store') }}" method="POST">
                    @csrf
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-slate-900 dark:text-white" id="modal-title">
                                    Nuevo Grupo de Cámaras
                                </h3>
                                <div class="mt-2">
                                    <input type="text" name="name" required autofocus
                                           class="w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none placeholder-slate-400" 
                                           placeholder="Ej: Zona Norte, Almacén...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/30 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Guardar Grupo
                        </button>
                        <button @click="showGroupModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection