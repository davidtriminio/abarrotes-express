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


    public function crearReporte()
    {

        $this->validate([
            'titulo' => 'required|string|max:30',
            'seccion' => 'required|string|in:Inicio,Productos,Categorias,Marcas,Cupones,Carrito,Perfil,Panel Administrativo',
            'descripcion' => 'required|string|max:500',
        ]);


        ReporteProblema::create([
            'titulo' => $this->titulo,
            'seccion' => $this->seccion,
            'descripcion' => $this->descripcion,
        ]);


        $this->alert('success', 'Problema reportado con exito');


        $this->titulo = '';
        $this->seccion = '';
        $this->descripcion = '';
    }


    public function render()
    {
        return view('livewire.reporte-problema');
    }
}
