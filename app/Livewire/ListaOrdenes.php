<?php

namespace App\Livewire;

use App\Models\Orden;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

class ListaOrdenes extends Component
{
    #[Title('Mis Ordenes - Abarrotes Express')]

    public $estado;

    public function mount($estado = null)
    {
        $this->estado = $estado;
    }

    public function render()
    {
        $query = Orden::where('user_id', auth()->user()->id);


        if ($this->estado) {
            $query->where('estado_entrega', $this->estado);
        }

        $mis_ordenes = $query->latest()->paginate(10);

        return view('livewire.lista-ordenes', [
            'ordenes' => $mis_ordenes
        ]);
    }
}
