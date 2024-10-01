<?php

namespace App\Livewire;

use App\Models\Cupon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DetalleCupon extends Component
{
    public $perPage = 9;


    public function render()
    {
        $userId = Auth::id();

        // Aplicar los mismos filtros que en el método actualizarCarrito
        $cupones = Cupon::where('estado', true)  // Solo cupones activos
        ->where('fecha_inicio', '<=', now())
        ->where('fecha_expiracion', '>', now())  // Solo cupones no caducados
        ->where('usuario_id', $userId)  // Solo los cupones del usuario autenticado
        ->paginate($this->perPage);

        return view('livewire.detalle-cupon', [
            'cupones' => $cupones,
        ]);
    }
}
