<?php

namespace App\Livewire;

use App\Livewire\Complementos\Navbar;
use App\Models\Producto;
use App\Helpers\CarritoManagement;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DetalleProducto extends Component
{
    use LivewireAlert;

    public $id;
    public $cantidad = 1;  // Inicializamos la cantidad en 1

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        $producto = Producto::findOrFail($this->id);
        return view('livewire.producto', compact('producto'));
    }

    public function agregarAlCarrito($producto_id)
    {
        $total_count = CarritoManagement::agregarElementosAlCarritoConCantidad($producto_id, $this->cantidad);
        $this->dispatch('update-conteo_total', total_count: $total_count)->to(Navbar::class);
        $this->alert('success', 'El producto fue agregado al carrito', [
            'position' => 'bottom-end',
            'timer' => 2000,
            'toast' => true,
            'timerProgressBar' => true,
        ]);
    }

    public function incrementarCantidad(){
        $this->cantidad ++;
    }

    public function decrementarCantidad(){
        if ($this->cantidad > 1){
            $this->cantidad --;
        }
    }

}
