<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Categoria;
use Livewire\WithoutUrlPagination;

class MostrarCategoria extends Component
{
    public $perPage = 9;


    public function render()
    {
        $categorias = Categoria::where('disponible', 1)->paginate($this->perPage);

        return view('livewire.mostrar-categoria', [
            'categorias' => $categorias,
        ]);
    }
}
