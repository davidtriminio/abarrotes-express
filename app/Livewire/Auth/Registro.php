<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Asegúrate de importar Auth

class Registro extends Component
{
    use LivewireAlert;
    public $id;
    public $name;
    public $email;
    public $password;
    public $telefono; // Propiedad para teléfono, opcional
    public $email_verified_at;
    public $user;

    public function render()
    {
        return view('livewire.auth.registro');
    }

    public function guardar()
    {
        $this->validate([
            'name' => 'required|regex:/^[a-zA-Z0-9_ áéíóúñÑ]+$/',
            'email' => 'required|email|unique:users,email|max:255',
            'telefono' => 'nullable|string|size:8|unique:users,telefono', // Teléfono es opcional
            'password' => 'required|min:8|max:30|regex:/[A-Z]/|regex:/[a-z]/|regex:/[\W]+$/',
        ],
            [
                'name.required' => 'El nombre de usuario es obligatorio.',
                'name.regex' => 'El nombre de usuario solo tiene que tener letra, espacio o guión bajo.',
                'email.required' => 'El campo correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
                'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
                'email.unique' => 'Correo electrónico ya existe.',
                'telefono.size' => 'El teléfono debe tener exactamente 8 dígitos.',
                'telefono.unique' => 'El número de teléfono ya está registrado.',
                'password.required' => 'El campo contraseña es obligatorio.',
                'password.max' => 'La contraseña no puede tener más 30 caracteres.',
                'password.min' => 'La contraseña no puede tener menos de 8 caracteres.',
                'password.regex' => 'La contraseña solo tiene que tener mayúscula, minúscula y caracteres especiales.',
            ]);

        // Crear un nuevo registro en la base de datos
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->telefono = $this->telefono;
        $user->password = Hash::make($this->password);
        $user->recovery_key = Str::random(30);
        $user->email_verified_at = Carbon::now();
        $user->assignRole('Cliente');
        $user->save();


        Auth::login($user);

        $this->reset();

        $this->alert('success', '¡Registro exitoso!', [
            'timer' => 3000,
        ]);



        return redirect()->route('inicio');
    }
}

