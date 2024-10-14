<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class VerificarClave extends Component
{

    public $email;
    public $recovery_key;

    public function render()
    {
        return view('livewire.auth.verificar-clave');
    }

    public function verificarClave()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
            'recovery_key' => 'required|string|exists:users,recovery_key',
        ]);

        // Verificar que la clave de recuperación y el correo pertenezcan al mismo usuario
        $user = User::where('email', $this->email)
            ->where('recovery_key', $this->recovery_key)
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'recovery_key' => 'Clave de recuperación incorrecta o no coincide con el correo.',
            ]);
        }

        // Si todo está bien, redirigir a la vista de cambiar contraseña
        session()->flash('email', $this->email); // Pasar el correo a la siguiente vista
        return redirect()->route('cambiarcontrasena');
    }

}
