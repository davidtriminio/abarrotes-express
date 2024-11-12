<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Marca;

class MostrarMarca extends Component
{
    public $perPage = 12;

    public function render()
    {
        $marcas = Marca::where('disponible', 1)->paginate($this->perPage);

        return view('livewire.mostrar-marca', [
            'marcas' => $marcas,
        ]);
    }
}
