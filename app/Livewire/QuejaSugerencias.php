<?php

namespace App\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\QuejaSugerencia;

class QuejaSugerencias extends Component
{
    use LivewireAlert;

    public $titulo;
    public $tipo;
    public $descripcion;

    public function crearQuejaSugerencia()
    {
        $this->validate([
            'titulo' => 'required|string|max:30',
            'tipo' => 'required|string|in:Queja,Sugerencia',
            'descripcion' => 'required|string|max:500',
        ]);

        QuejaSugerencia::create([
            'titulo' => $this->titulo,
            'tipo' => $this->tipo,
            'descripcion' => $this->descripcion,
        ]);

        $this->alert('success', 'Queja o sugerencia creada con éxito.');


        $this->titulo = '';
        $this->tipo = '';
        $this->descripcion = '';
    }

    public function render()
    {
        return view('livewire.queja-sugerencia');
    }
}
