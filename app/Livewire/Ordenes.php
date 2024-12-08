<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Orden;
use App\Models\User;
use App\Models\Producto;
use App\Models\ElementoOrden;


class Ordenes extends Component
{

    public $id;
    public $confirmingReturn = false;

    public function iniciarDevolucion()
    {
        $orden = Orden::findOrFail($this->id);
        
        // Check if order status allows for return
        $orden = Orden::findOrFail($this->id);
        if (in_array($orden->estado_entrega, ['nuevo', 'procesado', 'entregado'])) {
            $this->confirmingReturn = true;
        }
    }

    public function procesarDevolucion()
{
    $orden = Orden::findOrFail($this->id);

    // Debugging line
    \Log::info('Estado entrega antes de eliminar: ' . $orden->estado_entrega);
    
    try {
        // Eliminar la orden
        $orden->delete();
        
        // Mensaje de éxito
        $this->confirmingReturn = false;
        session()->flash('message', 'Devolución procesada y orden eliminada con éxito.');

        // Redirigir a la ruta deseada
        return redirect()->route('ordenes', ['id' => $this->id]);
    } catch (\Exception $e) {
        // Manejo de errores
        \Log::error('Error al procesar la devolución: ' . $e->getMessage());
        session()->flash('error', 'Error al procesar la devolución.');
    }
}
    
    public function render()
    {   
        $orden = Orden::with(['user', 'elementos.producto'])->findOrFail($this->id);

        if (auth()->user()->id !== $orden->user_id) {
            abort(403, 'No tienes permiso para ver esta orden.');
        }
        
        return view('livewire.ordenes', [
        'orden' => $orden,
        
    ]);
    }
}