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


        $cupones = Cupon::where('estado', true)
        ->where('fecha_inicio', '<=', now())
            ->where('fecha_expiracion', '>', now())
            ->where('user_id', $userId)
            ->paginate($this->perPage);

        return view('livewire.detalle-cupon', [
            'cupones' => $cupones,
        ]);
    }
}
