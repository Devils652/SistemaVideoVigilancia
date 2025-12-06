<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use App\Models\CameraGroup; // <--- Usamos el modelo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CameraController extends Controller
{
    use AuthorizesRequests;

    private function getIpValidationRules()
    {
        return ['required', 'string', function ($attribute, $value, $fail) {
            $isIp = filter_var($value, FILTER_VALIDATE_IP);
            $isUrl = filter_var($value, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\//', $value);
            $isYoutube = str_contains($value, 'youtube.com') || str_contains($value, 'youtu.be');

            if (!$isIp && !$isUrl && !$isYoutube) {
                $fail("La dirección debe ser una IP válida, una URL (http/https) o un video de YouTube.");
            }
        }];
    }

    public function index(Request $request)
    {
        $this->authorize('ver_camaras');

        $userRole = Auth::user()->role?->name ?? 'user';
        $query = Camera::query();

        if (!in_array($userRole, ['admin', 'supervisor', 'mantenimiento'])) {
            $query->where('status', true);
        }

        $cameras = $query->orderBy('name')->get();

        $groupedCameras = $cameras->groupBy(function ($item) {
            return $item->group ?: 'Sin Grupo';
        });

        $sinGrupo = $groupedCameras->pull('Sin Grupo');
        $groupedCameras = $groupedCameras->sortKeys();
        
        if ($sinGrupo) {
            $groupedCameras->put('Sin Grupo', $sinGrupo);
        }

        return view('cameras.index', compact('groupedCameras'));
    }

    public function storeGroup(Request $request)
    {
        $this->authorize('crear_camaras');
        $request->validate([
            'name' => 'required|string|max:255|unique:camera_groups,name'
        ]);
        CameraGroup::create(['name' => $request->name]);
        return back()->with('success', 'Grupo creado exitosamente.');
    }

    public function create()
    {
        $this->authorize('crear_camaras');
        $groups = CameraGroup::all(); // <--- Enviamos los grupos
        return view('cameras.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $this->authorize('crear_camaras');

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'ip'       => $this->getIpValidationRules(),
            'location' => 'nullable|string|max:255',
            'status'   => 'required|boolean',
            'group'    => 'nullable|string|max:255',
        ]);

        Camera::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route($this->getRedirectRoute())->with('success', 'Cámara registrada.');
    }

    public function edit(Camera $camera)
    {
        $this->authorize('editar_camaras');
        $groups = CameraGroup::all(); // <--- Enviamos los grupos aquí también
        return view('cameras.edit', compact('camera', 'groups'));
    }

    public function update(Request $request, Camera $camera)
    {
        $this->authorize('editar_camaras');

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'ip'       => $this->getIpValidationRules(),
            'location' => 'nullable|string|max:255',
            'status'   => 'required|boolean',
            'group'    => 'nullable|string|max:255',
        ]);

        $camera->update($validated);

        return redirect()->route($this->getRedirectRoute())->with('success', 'Cámara actualizada.');
    }

    // ... (Resto del archivo: show, destroy, getRedirectRoute, multiview, sin cambios) ...
    public function show(Camera $camera) { $this->authorize('ver_camaras'); return view('cameras.show', compact('camera')); }
    public function destroy(Camera $camera) { $this->authorize('borrar_camaras'); $camera->delete(); return redirect()->route($this->getRedirectRoute())->with('success', 'Cámara eliminada.'); }
    private function getRedirectRoute() {
        $role = Auth::user()->role?->name ?? 'user';
        return match ($role) {
            'admin' => 'admin.cameras.index',
            'supervisor' => 'supervisor.cameras.index',
            'mantenimiento' => 'mantenimiento.cameras.index',
            default => 'user.cameras.index',
        };
    }
    public function multiview() { $this->authorize('ver_camaras'); $cameras = Camera::where('status', true)->orderBy('name')->get(); return view('cameras.multiview', compact('cameras')); }
}