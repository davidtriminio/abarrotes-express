<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\EnviarCorreos;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class VerificarClave extends Component
{
    use LivewireAlert;

    public $recovery_key;
    public $email;

    public function render()
    {
        return view('livewire.auth.verificar-clave');
    }

    public function verificarClave()
    {

        $this->validate([
            'recovery_key' => 'required|string|exists:users,recovery_key',
        ]);


        $user = User::where('recovery_key', $this->recovery_key)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'recovery_key' => 'Clave de recuperación incorrecta.',
            ]);
        }


        session(['user_id' => $user->id]);
        return redirect()->route('cambiarcontrasena');
    }


    public function verificarCorreo()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Correo electrónico no encontrado.',
            ]);
        }

        // Verificar si ya existe un token y si ha expirado
        if ($user->password_reset_token && $user->token_expires_at > now()) {
            // El token existe y es válido, reutiliza el token
            $token = $user->password_reset_token;
        } else {
            // Generar un nuevo token porque no existe o ha expirado
            $token = Str::random(60);
            $user->password_reset_token = $token; // Asignar el nuevo token
            $user->token_expires_at = now()->addMinutes(30); // Establecer nueva expiración
        }

        $user->save(); // Guardar cambios en la base de datos

        // Generar el enlace de restablecimiento
        $resetLink = route('cambiarcontrasena', ['token' => $token]);

        // Enviar correo con el enlace de restablecimiento
        Mail::to($user->email)->send(new EnviarCorreos($user, $resetLink));

        $this->alert('success', 'Enlace de recuperación enviado');
    }







}
