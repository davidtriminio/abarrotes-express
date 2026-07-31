<?php

namespace App\Livewire;

use App\Models\Orden;
use Livewire\Component;

class ExitoPedido extends Component
{
    public function render()
    {
        $ultima_orden = Orden::with('direccion')->where('user_id', auth()->id())->latest()->first();
        if ($ultima_orden) {
            return view('livewire.exito-pedido', [
                'orden' => $ultima_orden
            ]);
        } else {
           return view('livewire.exito-pedido', [ 'orden' => null]);
        }
    }
}
