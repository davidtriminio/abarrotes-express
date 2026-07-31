<?php

namespace App\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\ReporteProblema;

class ReporteProblemas extends Component
{
    use LivewireAlert;

    public $titulo;
    public $seccion;
    public $descripcion;

    protected $rules = [
        'titulo' => 'required|string|max:30|regex:/^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ]+$/',
        'seccion' => 'required|string|in:Inicio,Productos,Categorias,Marcas,Cupones,Carrito,Perfil,Panel Administrativo',
        'descripcion' => 'required|string|max:500|regex:/^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ.,!?]+$/',
    ];

    protected $messages = [
        'titulo.required' => 'El título es obligatorio.',
        'titulo.string' => 'El título debe ser una cadena de texto.',
        'titulo.max' => 'El título no puede superar los 30 caracteres.',
        'titulo.regex' => 'El titulo solo debe contener letras.',
        'seccion.required' => 'Debe seleccionar una sección.',
        'seccion.in' => 'La sección seleccionada no es válida.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'descripcion.string' => 'La descripción debe ser una cadena de texto.',
        'descripcion.max' => 'La descripción no puede superar los 500 caracteres.',
        'descripcion.regex' => 'La descripcion solo debe contener letras y numeros',
    ];

    public function crearReporte()
    {
        $this->validate();

        ReporteProblema::create([
            'titulo' => $this->titulo,
            'seccion' => $this->seccion,
            'descripcion' => $this->descripcion,
        ]);

        $this->alert('success', 'Problema reportado con éxito.');

        $this->reset(['titulo', 'seccion', 'descripcion']);
    }

    public function render()
    {
        return view('livewire.reporte-problema');
    }
}
