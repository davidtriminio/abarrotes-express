<?php

namespace App\Livewire;

use App\Models\Orden;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Perfil extends Component
{
    public $nombreUsuario;


    public $notificaciones = [];


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

    public function obtenerNotificaciones()
    {
        $userId = Auth::id();

        // Obtener las órdenes con estado cambiado recientemente
        $this->notificaciones = Orden::where('user_id', $userId)
            ->whereIn('estado_entrega', ['procesado', 'enviado', 'entregado'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($orden) {
                return [
                    'mensaje' => "La orden #{$orden->id} ha cambiado a estado '{$orden->estado_entrega}'",
                    'fecha' => $orden->updated_at->diffForHumans(),
                ];
            });
    }

    public function redirigirOrdenes($estado)
    {
        return redirect()->route('ordenes', ['estado' => $estado]);
    }



    public function mount()
    {

        $this->nombreUsuario = Auth::user()->name;
        $this->actualizarContadores();
        $this->obtenerNotificaciones();
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
