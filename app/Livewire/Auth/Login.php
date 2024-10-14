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

        $this->resetErrorBag();
        $this->resetValidation();


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


        $throttleKey = strtolower($this->email);


        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {

            $seconds = RateLimiter::availableIn($throttleKey);


            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;


            $timeRemaining = "{$minutes} minuto(s) y {$remainingSeconds} segundo(s)";

            throw ValidationException::withMessages([
                'email' => ["Has excedido los intentos. Inténtalo nuevamente en {$timeRemaining}."],
            ]);
        }


        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {

            RateLimiter::hit($throttleKey, 300);


            session()->flash('error', 'Correo y contraseña no coinciden.');
        } else {

            RateLimiter::clear($throttleKey);

            return redirect()->route('inicio');
        }
    }
}
