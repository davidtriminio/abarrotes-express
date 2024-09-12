<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Marca;

class MostrarMarca extends Component
{
    #[Url]
    public $perPage = 9;
    public $search = '';

    public function render()
    {
        $marcas = Marca::where('nombre', 'like', '%' . $this->search . '%')
            ->where('disponible', 1)
            ->paginate($this->perPage);

        return view('livewire.mostrar-marca', [
            'marcas' => $marcas,
        ]);
    }
}
