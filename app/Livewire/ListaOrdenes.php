<?php

namespace App\Livewire;

use App\Models\Orden;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

class ListaOrdenes extends Component
{
    #[Title('Mis Ordenes - Abarrotes Express')]

    public $id=0;
    public function render()
    {
        $mis_ordenes = Orden::where('user_id', auth()->user()->id)->latest()->paginate(10);
        return view('livewire.lista-ordenes', [
            'ordenes' => $mis_ordenes
        ]);
    }
}
