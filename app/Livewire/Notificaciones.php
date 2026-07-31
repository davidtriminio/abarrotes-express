<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Orden;
use Livewire\WithPagination;

class Notificaciones extends Component
{
    use WithPagination;

    public function obtenerNotificaciones()
    {
        $userId = Auth::id();

        if (!$userId) {
            return collect([]);
        }

        // Usar paginación
        return Orden::where('user_id', $userId)
            ->whereIn('estado_entrega', ['procesado', 'enviado', 'entregado'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->through(function ($orden) {
                return [
                    'id' => $orden->id,
                    'mensaje' => "La orden #{$orden->id} ha cambiado a estado '{$orden->estado_entrega}'",
                    'fecha' => $orden->updated_at->format('d-m-Y H:i'),
                    'ruta' => route('mi_orden', ['id' => $orden->id]),
                ];
            });
    }

    public function render()
    {
        return view('livewire.notificaciones', [
            'notificaciones' => $this->obtenerNotificaciones(),
        ]);
    }
}
