<?php

namespace App\Livewire;

use App\Models\Orden;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Perfil extends Component
{
    public $nombreUsuario;

    // Contadores para cada estado de las órdenes
    public $contadorNuevo = 0;
    public $contadorProceso = 0;
    public $contadorEntregado = 0;
    public $contadorTerminado = 0;

    public function exportarClaveRecuperacion()
    {
        // Obtener la clave de recuperación del usuario autenticado
        $user = Auth::user();
        $recoveryKey = $user->recovery_key;

        // Definir el nombre del archivo
        $fileName = 'CLAVE DE RECUPERACION.txt';

        // Crear el contenido del archivo
        $fileContent = "Tu clave de recuperación es: " . $recoveryKey;

        // Crear el archivo temporal en el sistema
        Storage::put($fileName, $fileContent);

        // Retornar la respuesta para la descarga
        return response()->download(storage_path("app/{$fileName}"))->deleteFileAfterSend(true);
    }

    public function mount()
    {
        // Obtener el nombre del usuario
        $this->nombreUsuario = Auth::user()->name;

        // Inicializamos los contadores de órdenes por estado
        $this->contadorNuevo = Orden::where('user_id', auth()->id())->where('estado_entrega', 'nuevo')->count();
        $this->contadorProceso = Orden::where('user_id', auth()->id())->where('estado_entrega', 'procesado')->count();
        $this->contadorEntregado = Orden::where('user_id', auth()->id())->where('estado_entrega', 'entregado')->count();
        $this->contadorTerminado = Orden::where('user_id', auth()->id())->where('estado_entrega', 'terminado')->count();
    }

    public function render()
    {
        return view('livewire.perfil');
    }
}
