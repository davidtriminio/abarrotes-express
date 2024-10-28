<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CambiarContrasena extends Component
{

    use LivewireAlert;

    public $new_password;
    public $confirm_password;
    public $user_id;
    public $token;

    public function mount($token = null)
    {
        // Establecer el token desde la URL o desde la clave de recuperación
        $this->token = $token;
        $this->user_id = session('user_id');

        // Si no se proporciona un token y no hay user_id, redirigir
        if (!$this->token && !$this->user_id) {
            return redirect()->route('verificarclave');
        }
    }

    public function render()
    {
        return view('livewire.auth.cambiar-contrasena');
    }

    public function cambiarContrasena()
    {
        // Validación de los campos de la nueva contraseña
        $this->validate([
            'new_password' => 'required|min:8|max:30|regex:/[A-Z]/|regex:/[a-z]/|regex:/[\W]+/',
            'confirm_password' => 'required|same:new_password',
        ], [
            'new_password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula y un carácter especial.',
            'confirm_password.same' => 'La confirmación de la contraseña no coincide.',
        ]);

        $user = null;

        // Manejo de recuperación por token de correo
        if ($this->token) {
            $user = User::where('password_reset_token', $this->token)
                ->where('token_expires_at', '>', now()) // Verifica si el token no ha expirado
                ->first();

            // Verificar si el token ha expirado
            if (!$user) {
                // Si no se encuentra al usuario o el token ha expirado, eliminar el token
                return redirect()->route('verificarclave')->with('error', 'El token ha expirado o es inválido. Por favor, solicita un nuevo enlace de recuperación.');
            }
        }
        // Manejo de recuperación por clave de recuperación
        elseif ($this->user_id) {
            $user = User::find($this->user_id);
            if (!$user || $user->recovery_key !== $this->recovery_key) {
                return redirect()->route('verificarclave')->with('error', 'Clave de recuperación incorrecta.');
            }
        }

        // Verificar si el usuario fue encontrado
        if (!$user) {
            return redirect()->route('verificarclave')->with('error', 'El token o la clave de recuperación son inválidos o han expirado.');
        }

        // Actualizar la contraseña
        $user->password = Hash::make($this->new_password);
        $user->password_reset_token = null;
        $user->token_expires_at = null;
        $user->save();

        // Iniciar sesión
        auth()->login($user);

        $this->alert('success', 'Contraseña actualizada con éxito');
        return redirect()->route('inicio');
    }






}
