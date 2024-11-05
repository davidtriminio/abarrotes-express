<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Orden;
use App\Models\User;
use App\Models\Producto;
use App\Models\Elemento;


class Ordenes extends Component
{

    public $id;
    public function render()
    {
        $orden = Orden::with(['user'])->findOrFail($this->id);
        return view('livewire.ordenes', [
        'orden' => $orden,
    ]);
    }
}
