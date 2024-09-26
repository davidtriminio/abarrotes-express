<?php

namespace App\Livewire;

use App\Helpers\CarritoManagement;
use App\Livewire\Complementos\Navbar;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Cupon;

class Carrito extends Component
{
    use LivewireAlert;

    #[Title('Carrito')]
    public $elementos_carrito;
    public $total_original;
    public $total_final;
    public $cupones;
    public $cupones_aplicados = [];
    public $mostrar_menu_cupones = false;
    public $descuento_total = 0;
    public $usuario_autenticado = false;
    public $mostrar_modal_cupon = false;

    public $cupon_a_reemplazar;
    public $nuevo_cupon_id;

    protected $listeners = ['userLoggedIn' => 'actualizarCarrito'];

    public function mount()
    {
        $this->usuario_autenticado = Auth::check();
        $this->actualizarCarrito();
        $this->elementos_carrito = CarritoManagement::obtenerElementosDeCookies();
        $this->total_original = CarritoManagement::calcularTotalFinal($this->elementos_carrito);
        $this->total_final = $this->total_original;
    }

    public function eliminarElemento($producto_id)
    {
        $this->elementos_carrito = CarritoManagement::quitarElementosCarrito($producto_id);
        $this->total_original = CarritoManagement::calcularTotalFinal($this->elementos_carrito);
        $this->total_final = $this->total_original;
        $this->descuento_total = 0;
        $this->cupones_aplicados = [];
        $this->dispatch('update-cart-count', conteo_total: count($this->elementos_carrito))->to(Navbar::class);
    }

    public function incrementarCantidad($producto_id)
    {
        $resultado = CarritoManagement::incrementarCantidadElementosCarrito($producto_id);

        if (is_array($resultado)) {
            $this->elementos_carrito = $resultado;
            $this->total_original = CarritoManagement::calcularTotalFinal($this->elementos_carrito);
            $this->total_final = $this->total_original - $this->descuento_total;
        } else {
            $this->alert('error', $resultado, [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
                'timerProgressBar' => true,
                $this->skipRender()
            ]);
        }
    }

    public function decrementarCantidad($producto_id)
    {
        $resultado = CarritoManagement::decrementarCantidadElementosCarrito($producto_id);

        if (is_array($resultado)) {
            $this->elementos_carrito = $resultado;
            $this->total_original = CarritoManagement::calcularTotalFinal($this->elementos_carrito);
            $this->total_final = $this->total_original - $this->descuento_total;
        } else {
            $this->alert('error', $resultado, [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
                'timerProgressBar' => true,
                $this->skipRender()
            ]);
        }
    }

    public function toggleMenuCupones()
    {

        if (count($this->elementos_carrito) > 0) {
            $this->mostrar_menu_cupones = !$this->mostrar_menu_cupones;
        } else {
            // Mostrar alerta si el carrito está vacío
            $this->alert('error', 'Para agregar cupones, debe tener productos en el carrito.', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
                'timerProgressBar' => true,
            ]);
        }
    }

    public function aplicarCupon($cupon_id)
    {
        if (!$this->usuario_autenticado) {
            $this->alert('error', 'Debes iniciar sesión para aplicar un cupón.');
            return;
        }

        if (count($this->cupones_aplicados) > 0) {
            $this->cupon_a_reemplazar = $this->cupones_aplicados[0];
            $this->nuevo_cupon_id = $cupon_id;
            $this->mostrar_modal_cupon = true;
            return;
        }


        $this->procesarAplicacionCupon($cupon_id);

        $cupon = Cupon::find($cupon_id);
        if ($cupon) {
            if (!in_array($cupon_id, $this->cupones_aplicados)) {
                if ($cupon->tipo_descuento === 'porcentaje') {
                    $descuento = $this->total_final * ($cupon->descuento_porcentaje / 100);
                } else {
                    $descuento = $cupon->descuento_dinero;
                }
                $this->total_final -= $descuento;
                $this->descuento_total += $descuento;
                $this->cupones_aplicados = [$cupon_id];
            }
        }

        $this->alert('success', 'Cupón aplicado correctamente.');


        $this->mostrar_menu_cupones = false;
    }

    public function confirmarReemplazoCupon()
    {
        if ($this->cupon_a_reemplazar) {
            $this->retirarCupon($this->cupon_a_reemplazar);
            $this->procesarAplicacionCupon($this->nuevo_cupon_id);
            $this->mostrar_modal_cupon = false;
            $this->mostrar_menu_cupones = false;
            $this->alert('success', 'Cupón aplicado correctamente.');
        }
    }

    private function procesarAplicacionCupon($cupon_id)
    {
        $cupon = Cupon::find($cupon_id);
        if ($cupon) {
            if (!in_array($cupon_id, $this->cupones_aplicados)) {
                if ($cupon->tipo_descuento === 'porcentaje') {
                    $descuento = $this->total_final * ($cupon->descuento_porcentaje / 100);
                } else {
                    $descuento = $cupon->descuento_dinero;
                }
                $this->total_final -= $descuento;
                $this->descuento_total += $descuento;
                $this->cupones_aplicados = [$cupon_id];
            }
        }
    }

    public function retirarCupon($cupon_id)
    {
        $cupon = Cupon::find($cupon_id);
        if ($cupon) {
            if (($key = array_search($cupon_id, $this->cupones_aplicados)) !== false) {
                unset($this->cupones_aplicados[$key]);
                $this->total_final = $this->total_original;
                $this->descuento_total = 0;

                foreach ($this->cupones_aplicados as $aplicado_id) {
                    $cupon_aplicado = Cupon::find($aplicado_id);
                    if ($cupon_aplicado) {
                        if ($cupon_aplicado->tipo_descuento === 'porcentaje') {
                            $descuento = $this->total_final * ($cupon_aplicado->descuento_porcentaje / 100);
                        } else {
                            $descuento = $cupon_aplicado->descuento_dinero;
                        }
                        $this->total_final -= $descuento;
                        $this->descuento_total += $descuento;
                    }
                }
            }
        }
    }

    public function actualizarCarrito()
    {
        if ($this->usuario_autenticado) {
            $this->cupones = Cupon::where('estado', true)
                ->where('fecha_expiracion', '>', now())
                ->where('usuario_id', Auth::id())
                ->get();
        } else {
            $this->cupones = [];
        }
    }



    public function render()
    {
        return view('livewire.carrito');
    }
}
