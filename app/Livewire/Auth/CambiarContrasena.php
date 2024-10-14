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
    public $email;
    public $new_email;

    public function mount()
    {
        // Recuperar el email desde la sesión
        $this->email = session('email');

        if (!$this->email) {
            // Si no hay email en la sesión, redirigir de vuelta a la vista de verificar clave
            return redirect()->route('verificarclave');
        }
    }

    public function render()
    {
        return view('livewire.auth.cambiar-contrasena');
    }

    public function cambiarContrasena()
    {

        $this->validate([
            'new_password' => 'required|min:8|max:30|regex:/[A-Z]/|regex:/[a-z]/|regex:/[\W]+/',
            'confirm_password' => 'required|same:new_password',
            'new_email' => 'nullable|email|unique:users,email',
        ], [
            'new_password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula y un carácter especial.',
            'confirm_password.same' => 'La confirmación de la contraseña no coincide.',
            'new_email.unique' => 'El correo electrónico ya está en uso.',
        ]);


        $user = User::where('email', $this->email)->first();

        if (!$user) {

            return redirect()->route('verificarclave');
        }


        $user->password = Hash::make($this->new_password);


        if ($this->new_email) {
            $user->email = $this->new_email;
        }

        // Guardar los cambios en la base de datos
        $user->save();

        // Mostrar alerta de éxito con LivewireAlert
        $this->alert('success', 'Datos actualizados con éxito');

        // Redirigir al login (o puedes optar por no redirigir)
        return redirect()->route('login');
    }





}
