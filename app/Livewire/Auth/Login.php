<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class Login extends Component
{
    public $email;
    public $password;

    #[Title('Iniciar Sesion')]

    public function render()
    {
        return view('livewire.auth.login');
    }

    public function save()
    {
        // Reset errores previos
        $this->resetErrorBag();
        $this->resetValidation();

        // Validar los datos
        $this->validate([
            'email' => 'required|email|max:255|min:4|exists:users,email',
            'password' => 'required|min:4|max:300'
        ], [
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.exists' => 'Correo electrónico no encontrado.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.max' => 'La contraseña no puede tener más de 300 caracteres.',
            'password.min' => 'La contraseña no puede tener menos de 4 caracteres.',
        ]);

        // Definir key para limitar intentos por email
        $throttleKey = strtolower($this->email) . '|' . request()->ip();

        // Verificar si el usuario está bloqueado
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            // Obtener el tiempo restante del bloqueo
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => ['Has excedido los intentos. Inténtalo nuevamente en ' . Carbon::now()->addSeconds($seconds)->diffForHumans() . '.'],
            ]);
        }

        // Intento de autenticación
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            // Registrar un intento fallido
            RateLimiter::hit($throttleKey, 300); // 300 segundos = 5 minutos

            // Mostrar error
            session()->flash('error', 'Correo y contraseña no coinciden.');
        } else {
            // Si se autentica con éxito, limpiar los intentos fallidos
            RateLimiter::clear($throttleKey);

            return redirect()->route('inicio');
        }
    }
}
