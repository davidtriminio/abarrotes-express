<?php

namespace App\Livewire;

use App\Livewire\Complementos\Navbar;
use App\Models\Favorito;
use App\Models\Producto;
use App\Helpers\CarritoManagement;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DetalleProducto extends Component
{
    use LivewireAlert;

    public $id;
    public $cantidad = 1;  // Inicializamos la cantidad en 1

    public $esFavorito;

    public function mount($id)
    {
        $this->id = $id;

        // Verificar si el producto ya está en favoritos
        $this->esFavorito = Favorito::where('user_id', auth()->id())
            ->where('producto_id', $this->id)
            ->exists();
    }

    public function render()
    {
        $producto = Producto::findOrFail($this->id);
        return view('livewire.producto', compact('producto'));
    }

    public function agregarAlCarrito($producto_id)
    {
        $total_count = CarritoManagement::agregarElementosAlCarritoConCantidad($producto_id, $this->cantidad);

        // Verificar si la operación fue exitosa
        if (is_numeric($total_count)) {
            $this->dispatch('update-cart-count', ['conteo_total' => $total_count])->to(Navbar::class);
            $this->alert('success', 'El producto fue agregado al carrito', [
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

    public function incrementarCantidad(){
        $this->cantidad ++;
    }

    public function decrementarCantidad(){
        if ($this->cantidad > 1){
            $this->cantidad --;
        }
    }

    // Método para agregar a favoritos
    public function agregarFavorito()
    {
        Favorito::create([
            'user_id' => auth()->id(),
            'producto_id' => $this->id,
        ]);

        $this->esFavorito = true;

        // Emitir evento para actualizar el contador de favoritos
        $this->emitTo('perfil', 'actualizarContadorFavoritos');


        $this->alert('success', 'Producto agregado a favoritos', [
            'position' => 'bottom-end',
            'timer' => 2000,
            'toast' => true,
            'timerProgressBar' => true,
        ]);
    }

    // Método para eliminar de favoritos
    public function eliminarFavorito()
    {
        Favorito::where('user_id', auth()->id())
            ->where('producto_id', $this->id)
            ->delete();

        $this->esFavorito = false;

        // Emitir evento para actualizar el contador de favoritos
        $this->emitTo('perfil', 'actualizarContadorFavoritos');

        $this->alert('success', 'Producto eliminado de favoritos', [
            'position' => 'bottom-end',
            'timer' => 2000,
            'toast' => true,
            'timerProgressBar' => true,
        ]);
    }

    private function emitTo(string $string, string $string1)
    {
    }

}
