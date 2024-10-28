<?php

namespace App\Livewire;

use App\Models\Orden;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Perfil extends Component
{
    public $nombreUsuario;


    public $contadorNuevo = 0;
    public $contadorProceso = 0;
    public $contadorEnviado = 0;
    public $contadorEntregado = 0;

    public function exportarClaveRecuperacion()
    {

        $user = Auth::user();
        $recoveryKey = $user->recovery_key;


        $fileName = 'CLAVE DE RECUPERACION.txt';


        $fileContent = "Tu clave de recuperación es: " . $recoveryKey;


        Storage::put($fileName, $fileContent);


        return response()->download(storage_path("app/{$fileName}"))->deleteFileAfterSend(true);

    }

    public function redirigirOrdenes($estado)
    {
        return redirect()->route('ordenes', ['estado' => $estado]);
    }



    public function mount()
    {

        $this->nombreUsuario = Auth::user()->name;


        $this->actualizarContadores();
    }

    public function actualizarContadores()
    {

        $userId = Auth::id();

        // Contar las órdenes por estado
        $this->contadorNuevo = Orden::where('user_id', $userId)
            ->where('estado_entrega', 'nuevo')
            ->count();

        $this->contadorProceso = Orden::where('user_id', $userId)
            ->where('estado_entrega', 'procesado')
            ->count();

        $this->contadorEnviado = Orden::where('user_id', $userId)
            ->where('estado_entrega', 'enviado')
            ->count();

        $this->contadorEntregado = Orden::where('user_id', $userId)
            ->where('estado_entrega', 'entregado')
            ->count();
    }


    public function render()
    {
        return view('livewire.perfil');
    }
}
