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
    public $email;
    public $telefono; // Añade esta línea
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $this->nombre = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->telefono = Auth::user()->telefono;
    }

    public function actualizarPerfil()
    {
        // Validaciones
        $rules = [
            'nombre' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . Auth::id(),
            'telefono' => 'nullable|string|max:8|unique:users,telefono,' . Auth::id(),
            'current_password' => 'required_if:new_password,!=,null|string',
            'new_password' => 'nullable|string|confirmed',
        ];

        $this->validate($rules, [
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'Este correo ya está registrado.',
            'telefono.unique' => 'Este número de teléfono ya está registrado.', // Mensaje de error para teléfono
            'current_password.required_if' => 'Debe proporcionar su contraseña actual si desea completar los cambios',
            'new_password.confirmed' => 'La nueva contraseña y su confirmación no coinciden.',
        ]);

        // Verificar la contraseña actual
        if ($this->new_password && !Hash::check($this->current_password, Auth::user()->password)) {
            $this->addError('current_password', 'La contraseña actual es incorrecta.');
            return;
        }

        $user = Auth::user();

        // Actualizar nombre, correo y teléfono solo si se proporciona
        if ($this->nombre && $this->nombre !== $user->name) {
            $user->name = $this->nombre;
        }

        if ($this->email && $this->email !== $user->email) {
            $user->email = $this->email;
        }

        if ($this->telefono && $this->telefono !== $user->telefono) { // Agregar la actualización de teléfono
            $user->telefono = $this->telefono;
        }

        // Actualizar contraseña si se proporciona
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
