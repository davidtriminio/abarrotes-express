<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class DetallePedido extends Component
{
    #[Title('Proceder al pago - Abarrotes Express')]
    public $nombres;
    public $apellidos;
    public $telefono;
    public $departamento;
    public $municipio;
    public $ciudad;
    public $direccion_completa;
    public $metodo_pago;

    public function realizarPedido()
    {
        $this->validate([
            'nombres' => 'required|max:100|regex:/^[A-Za-z ]+$/',
            'apellidos' => 'required|max:100|regex:/^[A-Za-z ]+$/',
            'telefono' => 'required|max_digits:8|integer',
            'ciudad' => 'required|string',
            'departamento' => 'required|string',
            'municipio' => 'required|string',
            'metodo_pago' => 'required',
            'direccion_completa' => 'required|max:300|min:10'
        ], [
            'nombres.required' => 'Es necesario introducir uno o más nombres.',
            'nombres.max' => 'Los nombres no pueden superar los 100 caracteres.',
            'nombres.regex' => 'Los nombres solo pueden incluir letras y espacios.',

            'apellidos.required' => 'Es necesario introducir uno o más apellidos.',
            'apellidos.max' => 'Los apellidos no pueden superar los 100 caracteres.',
            'apellidos.regex' => 'Los apellidos solo pueden incluir letras y espacios.',

            'telefono.required' => 'Es necesario proporcionar un número de teléfono.',
            'telefono.max_digits' => 'El número de teléfono no puede tener más de 8 dígitos.',
            'telefono.integer' => 'El número de teléfono debe ser un valor numérico.',

            'ciudad.required' => 'Es necesario proporcionar una ciudad.',
            'ciudad.string' => 'La ciudad debe consistir en texto.',

            'departamento.required' => 'Es necesario proporcionar un departamento.',
            'departamento.string' => 'El departamento debe consistir en texto.',

            'municipio.required' => 'Es necesario proporcionar un municipio.',
            'municipio.string' => 'El municipio debe consistir en texto.',

            'direccion_completa.required' => 'Debe introducir una dirección',
            'direccion_completa.max' => 'Debe introducir una dirección de :max dígitos o más.',
            'direccion_completa.min' => 'Debe introducir una dirección de :min dígitos o más.',

            'metodo_pago.required' => 'Es necesario seleccionar un método de pago.',
        ]);
    }

    public function render()
    {
        return view('livewire.detalle-pedido');
    }
}
