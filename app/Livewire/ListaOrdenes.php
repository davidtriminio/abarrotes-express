<?php

namespace App\Livewire;

use App\Exports\MisOrdenesExport;
use App\Models\Orden;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Mis Órdenes')]
class ListaOrdenes extends Component
{
    public $estado;

    public function mount($estado = null)
    {
        $this->estado = $estado;
    }

    public function exportar()
    {
        return Excel::download(
            new MisOrdenesExport(auth()->id()),
            'mis-ordenes-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function render()
    {
        $query = Orden::where('user_id', auth()->user()->id);


        if ($this->estado) {
            $query->where('estado_entrega', $this->estado);
        }

        $mis_ordenes = $query->latest()->paginate(10);

        return view('livewire.lista-ordenes', [
            'ordenes' => $mis_ordenes
        ]);
    }
}
