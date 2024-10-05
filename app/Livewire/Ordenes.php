<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Orden;


class Ordenes extends Component
{

    public $id;
    public function render()
    {
        $ordenes = Orden::findOrFail($this->id);
        return view('livewire.ordenes', [
            'ordenes' => $ordenes,
        ]);
    }
}
