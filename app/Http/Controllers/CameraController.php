<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CameraController extends Controller
{
    use AuthorizesRequests;

    // Regla de validación reutilizable para seguridad
    private function getIpValidationRules()
    {
        return ['required', 'string', function ($attribute, $value, $fail) {
            // 1. ¿Es una IP válida? (Ej: 192.168.1.50)
            $isIp = filter_var($value, FILTER_VALIDATE_IP);
            
            // 2. ¿Es una URL segura? (Debe empezar por http:// o https://)
            $isUrl = filter_var($value, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\//', $value);
            
            // 3. ¿Es un link de YouTube? (Excepción para demos)
            $isYoutube = str_contains($value, 'youtube.com') || str_contains($value, 'youtu.be');

            if (!$isIp && !$isUrl && !$isYoutube) {
                $fail("La dirección ingresada no es segura. Debe ser una IP válida (192.168.x.x) o una URL que inicie con http:// o https://.");
            }
        }];
    }

public function index(Request $request)
    {
        $this->authorize('ver_camaras');

        $userRole = Auth::user()->role?->name ?? 'user';
        $query = Camera::query();

        // Filtro por rol
        if (!in_array($userRole, ['admin', 'supervisor', 'mantenimiento'])) {
            $query->where('status', true);
        }

        $cameras = $query->orderBy('name')->get();

        // --- LÓGICA DE AGRUPACIÓN ---
        // 1. Agrupar por el campo 'group'
        $groupedCameras = $cameras->groupBy(function ($item) {
            return empty($item->group) ? 'Sin Grupo' : $item->group;
        });

        // 2. Separar "Sin Grupo" para ponerlo al final
        $noGroupCameras = $groupedCameras->pull('Sin Grupo');

        // 3. Ordenar los grupos alfabéticamente
        $groupedCameras = $groupedCameras->sortKeys();

        // 4. Pegar "Sin Grupo" al final si existe
        if ($noGroupCameras) {
            $groupedCameras->put('Sin Grupo', $noGroupCameras);
        }

        return view('cameras.index', compact('groupedCameras'));
    }

    // Nueva función para guardar el grupo desde el Modal
    public function storeGroup(Request $request)
    {
        $this->authorize('crear_camaras'); // Usamos el mismo permiso
        
        $request->validate([
            'name' => 'required|string|max:255|unique:camera_groups,name'
        ]);

        \App\Models\CameraGroup::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Grupo creado exitosamente.');
    }

    public function create()
    {
        $this->authorize('crear_camaras');
        return view('cameras.create');
    }

    public function store(Request $request)
    {
        $this->authorize('crear_camaras');

        $validated = $request->validate([
            'name'     => 'required|string|max:255|regex:/^[\pL\s\d\-]+$/u', // Solo letras, números y guiones (Anti-XSS)
            'ip'       => $this->getIpValidationRules(), // <--- REGLA DE SEGURIDAD APLICADA
            'location' => 'nullable|string|max:255',
            'status'   => 'required|boolean',
            'group'    => 'nullable|string|max:255',
        ]);

        Camera::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route($this->getRedirectRoute())->with('success', 'Cámara registrada y validada correctamente.');
    }

    public function show(Camera $camera)
    {
        $this->authorize('ver_camaras');
        return view('cameras.show', compact('camera'));
    }

    public function edit(Camera $camera)
    {
        $this->authorize('editar_camaras');
        return view('cameras.edit', compact('camera'));
    }

    public function update(Request $request, Camera $camera)
    {
        $this->authorize('editar_camaras');

        $validated = $request->validate([
            'name'     => 'required|string|max:255|regex:/^[\pL\s\d\-]+$/u',
            'ip'       => $this->getIpValidationRules(), // <--- REGLA DE SEGURIDAD APLICADA
            'location' => 'nullable|string|max:255',
            'status'   => 'required|boolean',
            'group'    => 'nullable|string|max:255',
        ]);

        $camera->update($validated);

        return redirect()->route($this->getRedirectRoute())->with('success', 'Configuración de cámara actualizada.');
    }

    public function destroy(Camera $camera)
    {
        $this->authorize('borrar_camaras');
        $camera->delete();

        return redirect()->route($this->getRedirectRoute())->with('success', 'Dispositivo eliminado de forma segura.');
    }

    private function getRedirectRoute()
    {
        $role = Auth::user()->role->name ?? 'user';
        return match ($role) {
            'admin' => 'admin.cameras.index',
            'supervisor' => 'supervisor.cameras.index',
            'mantenimiento' => 'mantenimiento.cameras.index',
            default => 'user.cameras.index',
        };
    }

    public function multiview()
    {
        $this->authorize('ver_camaras');
        $cameras = Camera::where('status', true)->orderBy('name')->get();
        return view('cameras.multiview', compact('cameras'));
    }
}