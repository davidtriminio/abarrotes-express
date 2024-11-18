<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Orden;
use App\Models\User;
use App\Models\Producto;
use App\Models\ElementoOrden;


class Ordenes extends Component
{

    public $id;
    public function render()
    {   
        $orden = Orden::with(['user', 'elementos.producto'])->findOrFail($this->id);
        
        return view('livewire.ordenes', [
        'orden' => $orden,
        
    ]);
    }
}/*$producto=Producto::all();
        $elemento=ElementoOrden::all();//with(['orden','producto'])->findOrFail($this->id);*/