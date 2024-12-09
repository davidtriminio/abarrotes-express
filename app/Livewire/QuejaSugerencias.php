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

    protected $rules = [
        'titulo' => 'required|string|max:30|regex:/^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ]+$/',
        'tipo' => 'required|string|in:Queja,Sugerencia',
        'descripcion' => 'required|string|max:500|regex:/^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ.,!?]+$/',
    ];

    protected $messages = [
        'titulo.required' => 'El título es obligatorio.',
        'titulo.string' => 'El título debe ser una cadena de texto.',
        'titulo.max' => 'El título no puede superar los 30 caracteres.',
        'titulo.regex' => 'El titulo solo debe contener letras.',
        'tipo.required' => 'Debe seleccionar un tipo',
        'tipo.in' => 'El tipo debe ser una "Queja" o una "Sugerencia".',
        'descripcion.required' => 'La descripción es obligatoria.',
        'descripcion.string' => 'La descripción debe ser una cadena de texto.',
        'descripcion.max' => 'La descripción no puede superar los 500 caracteres.',
        'descripcion.regex' => 'La descripcion solo debe contener letras y numeros',
    ];

    public function crearQuejaSugerencia()
    {
        $this->validate();

        QuejaSugerencia::create([
            'titulo' => $this->titulo,
            'tipo' => $this->tipo,
            'descripcion' => $this->descripcion,
        ]);

        $this->alert('success', 'Queja o sugerencia creada con éxito.');

        $this->reset(['titulo', 'tipo', 'descripcion']);
    }

    public function render()
    {
        return view('livewire.queja-sugerencia');
    }
}
