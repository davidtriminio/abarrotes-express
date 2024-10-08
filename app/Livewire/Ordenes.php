<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Orden;
use App\Models\User;
use App\Models\Producto;


class Ordenes extends Component
{

    public $id;
    public function render()
    {
        $orden = Orden::findOrFail($this->id);
        $user = User::all();
        return view('livewire.ordenes', [
            'orden' => $orden,
            'user' => $user,
        ]);
    }
}
