<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EditarPerfil extends Component
{
    use LivewireAlert;

    public $nombre;
    public $telefono;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $this->nombre = Auth::user()->name;
        $this->telefono = Auth::user()->telefono;
    }

    public function actualizarPerfil()
    {
        // Validaciones
        $rules = [
            'nombre' => 'nullable|string|max:60|regex:/^[a-zA-Z0-9_ áéíóúñÑ]+$/',
            'telefono' => 'nullable|string|max:8|unique:users,telefono,' . Auth::id(),
            'current_password' => 'required|string', // Ahora siempre es obligatorio
            'new_password' => 'nullable|string|confirmed|min:8|max:30', // Validación para la nueva contraseña
        ];

        $this->validate($rules, [
            'nombre.max' => 'El nombre no puede tener más de 60 caracteres.',
            'telefono.unique' => 'Este número de teléfono ya está registrado.',
            'current_password.required' => 'Debe proporcionar su contraseña actual para realizar cualquier cambio.',
            'new_password.confirmed' => 'La nueva contraseña y su confirmación no coinciden.',
            'new_password.min' => 'La contraseña no puede tener menos de 8 caracteres.',
            'new_password.max' => 'La contraseña no puede tener más de 30 caracteres.', // Mensaje personalizado para max
        ]);

        // Verificar la contraseña actual para cualquier cambio
        if (!Hash::check($this->current_password, Auth::user()->password)) {
            $this->addError('current_password', 'La contraseña actual es incorrecta.');
            return;
        }

        $user = Auth::user();

        if ($this->nombre && $this->nombre !== $user->name) {
            $user->name = $this->nombre;
        }

        if ($this->telefono && $this->telefono !== $user->telefono) {
            $user->telefono = $this->telefono;
        }

        if ($this->new_password) {
            $user->password = Hash::make($this->new_password);
        }

        $user->save();

        $this->alert('success', 'Perfil actualizado exitosamente.');
        return redirect()->route('perfil');
    }

    public function render()
    {
        return view('livewire.editar-perfil');
    }
}
