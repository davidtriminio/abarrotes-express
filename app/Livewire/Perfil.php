<?php

namespace App\Livewire;

use App\Models\Favorito;
use Livewire\Component;

class Perfil extends Component
{

    public $contadorFavoritos = 0;

    protected $listeners = ['actualizarContadorFavoritos' => 'actualizarContador'];

    public function mount()
    {
        // Inicializamos el contador de favoritos cuando se carga el componente
        $this->contadorFavoritos = Favorito::where('user_id', auth()->id())->count();
    }

    public function actualizarContador()
    {
        // Actualizamos el contador de favoritos
        $this->contadorFavoritos = Favorito::where('user_id', auth()->id())->count();
    }
    public function render()
    {
        return view('livewire.perfil');
    }
}
