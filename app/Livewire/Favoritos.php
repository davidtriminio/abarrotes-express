<?php

namespace App\Livewire;

use App\Models\Favorito;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Livewire\Complementos\Navbar;
use App\Helpers\CarritoManagement;
use Livewire\Component;
use Livewire\WithPagination;

class Favoritos extends Component
{

    use WithPagination;
    use LivewireAlert;



    // Método para agregar el producto favorito al carrito
    // Método para agregar el producto favorito al carrito
    public function agregarAlCarrito($producto_id)
    {
        // Usa CarritoManagement para agregar el producto al carrito con una cantidad predeterminada de 1
        $total_count = CarritoManagement::agregarElementosAlCarritoConCantidad($producto_id, 1);

        // Emitir el evento para actualizar el conteo en la barra de navegación
        if (is_numeric($total_count)) {
            $this->dispatch('update-cart-count', ['conteo_total' => $total_count])->to(Navbar::class);

            // Mostrar un mensaje de confirmación al usuario
            $this->alert('success', 'Producto agregado al carrito', [
                'position' => 'bottom-end',
                'timer' => 2000,
                'toast' => true,
                'timerProgressBar' => true,
            ]);
        } else {
            $this->alert('error', $total_count, [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
                'timerProgressBar' => true,
            ]);
        }
    }



    public function render()
    {
        $favoritos = Favorito::where('user_id', auth()->id())->with('producto')->paginate(10);

        return view('livewire.favoritos', [
            'favoritos' => $favoritos,
        ]);
    }
}
