<?php

namespace App\Livewire;

use App\Filament\Resources\OrdenResource;
use App\Helpers\CarritoManagement;
use App\Livewire\Complementos\Navbar;
use App\Mail\PedidoRealizado;
use App\Models\Orden;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Cupon;
use Filament\Notifications\Actions\Action as ActionNotification;

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

        // Obtener descuento y cupones aplicados de las cookies
        $descuento_data = CarritoManagement::obtenerDescuentoDeCookies();
        $this->descuento_total = $descuento_data['descuento_total'];
        $this->cupones_aplicados = $descuento_data['cupones_aplicados'];

        // Asegurarse de que el total final no sea negativo
        $this->total_final = max(0, $this->total_original - $this->descuento_total);
    }

    public function eliminarElemento($producto_id)
    {
        // Eliminar el producto del carrito
        $this->elementos_carrito = CarritoManagement::quitarElementosCarrito($producto_id);
        $this->total_original = CarritoManagement::calcularTotalFinal($this->elementos_carrito);

        // Verificar los cupones aplicados y si alguno ya no es válido
        $this->verificarCuponesAplicados();

        // Asegurarse de que el total final no sea negativo
        $this->total_final = max(0, $this->total_original - $this->descuento_total);

        // Actualizar el conteo del carrito
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
            // Asegurarse de que el total final no sea negativo
            $this->total_final = max(0, $this->total_original - $this->descuento_total);


            $this->verificarCuponesAplicados();
        }
    }

    private function verificarCuponesAplicados()
    {
        $cupones_a_retirar = [];

        foreach ($this->cupones_aplicados as $cupon_id) {
            if (!$this->cupónEsAplicable($cupon_id)) {
                $cupones_a_retirar[] = $cupon_id;
            }
        }

        // Retirar los cupones no aplicables
        foreach ($cupones_a_retirar as $cupon_id) {
            $this->retirarCuponAutomaticamente($cupon_id);
        }

        // Actualiza la cookie después de la verificación
        CarritoManagement::agregarDescuentoCookies($this->descuento_total, $this->cupones_aplicados, $this->nuevo_cupon_id);
    }

    private function retirarCuponAutomaticamente($cupon_id)
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

            // Mensaje específico para retirar por incumplimiento de restricciones
            $this->alert('success', 'Cupón retirado porque ya no cumple restricciones ó condiciones.');

            // Guardar el descuento total actualizado en las cookies
            CarritoManagement::agregarDescuentoCookies($this->descuento_total, $this->cupones_aplicados, $this->nuevo_cupon_id);

            $this->mostrar_menu_cupones = false;
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

        $cupon = Cupon::find($cupon_id);

        if (!$cupon) {
            $this->alert('error', 'Cupón no válido.');
            return;
        }

        $errores = [];


        if ($cupon->tipo_descuento === 'dinero') {

            if (!is_null($cupon->compra_minima) && $this->total_final < $cupon->compra_minima) {
                $errores[] = 'un total mínimo de ' . number_format($cupon->compra_minima, 2) . ' lempiras';
            }

            // Validar que el descuento no exceda el total final del carrito
            if ($cupon->descuento_dinero > $this->total_final) {
                $errores[] = 'el descuento no puede ser mayor al total final del carrito (' . number_format($this->total_final, 2) . ' lempiras)';
            }


            $cantidad_productos = array_sum(array_column($this->elementos_carrito, 'cantidad'));
            if (!is_null($cupon->compra_cantidad) && $cantidad_productos < $cupon->compra_cantidad) {
                $errores[] = 'una cantidad mínima de ' . $cupon->compra_cantidad . ' productos';
            }


            if (is_null($cupon->compra_minima) && is_null($cupon->compra_cantidad) && $this->total_final < $cupon->descuento_dinero) {
                $errores[] = 'el total de la compra debe ser mayor o igual al descuento de ' . number_format($cupon->descuento_dinero, 2) . ' lempiras';
            }
        } elseif ($cupon->tipo_descuento === 'porcentaje') {

            $cantidad_productos = array_sum(array_column($this->elementos_carrito, 'cantidad'));
            if (!is_null($cupon->compra_cantidad) && $cantidad_productos < $cupon->compra_cantidad) {
                $errores[] = 'una cantidad mínima de ' . $cupon->compra_cantidad . ' productos';
            }
        }


        if (!empty($errores)) {
            $this->alert('error', 'Este cupón solo aplica para compras con ' . implode(' y ', $errores) . '.');
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

                // Asegurarnos de que el total final no sea negativo
                $this->total_final = max(0, $this->total_final - $descuento);

                $this->descuento_total += $descuento;
                $this->cupones_aplicados = [$cupon_id];
            }
        }

        $this->alert('success', 'Cupón aplicado correctamente.');



        CarritoManagement::agregarDescuentoCookies($this->descuento_total, $this->cupones_aplicados, $this->nuevo_cupon_id);


        $this->mostrar_menu_cupones = false;
    }

    public function cupónEsAplicable($cupon_id)
    {
        $cupon = Cupon::find($cupon_id);
        $errores = [];

        if (!$cupon) return false;

        if ($cupon->tipo_descuento === 'dinero') {
            if (!is_null($cupon->compra_minima) && $this->total_final < $cupon->compra_minima) {
                return false;
            }

            $cantidad_productos = array_sum(array_column($this->elementos_carrito, 'cantidad'));
            if (!is_null($cupon->compra_cantidad) && $cantidad_productos < $cupon->compra_cantidad) {
                return false;
            }

            if (is_null($cupon->compra_minima) && is_null($cupon->compra_cantidad) && $this->total_final < $cupon->descuento_dinero) {
                return false;
            }
        } elseif ($cupon->tipo_descuento === 'porcentaje') {
            $cantidad_productos = array_sum(array_column($this->elementos_carrito, 'cantidad'));
            if (!is_null($cupon->compra_cantidad) && $cantidad_productos < $cupon->compra_cantidad) {
                return false;
            }
        }
        return true;
    }

    public function confirmarReemplazoCupon()
    {
        if ($this->cupon_a_reemplazar) {
            $this->retirarCupon($this->cupon_a_reemplazar);
            $this->procesarAplicacionCupon($this->nuevo_cupon_id);
            $this->mostrar_modal_cupon = false;
            $this->mostrar_menu_cupones = false;

            CarritoManagement::agregarDescuentoCookies($this->descuento_total, $this->cupones_aplicados, $this->nuevo_cupon_id);
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

                // Restablecer el total final al total original
                $this->total_final = $this->total_original;
                $this->descuento_total = 0;

                // Recalcular los descuentos aplicados de los cupones restantes
                foreach ($this->cupones_aplicados as $aplicado_id) {
                    $cupon_aplicado = Cupon::find($aplicado_id);
                    if ($cupon_aplicado) {
                        if ($cupon_aplicado->tipo_descuento === 'porcentaje') {
                            $descuento = $this->total_final * ($cupon_aplicado->descuento_porcentaje / 100);
                        } else {
                            $descuento = $cupon_aplicado->descuento_dinero;
                        }

                        // Asegurar que el total final no sea negativo
                        $this->total_final = max(0, $this->total_final - $descuento);
                        $this->descuento_total += $descuento;
                    }
                }
            }

            $this->alert('success', 'Cupón retirado correctamente.');
            // Guardar el descuento total actualizado en las cookies
            CarritoManagement::agregarDescuentoCookies($this->descuento_total, $this->cupones_aplicados, $this->nuevo_cupon_id);

            $this->mostrar_menu_cupones = false;

        }
    }

    public function actualizarCarrito()
    {
        if ($this->usuario_autenticado) {
            $this->cupones = Cupon::where('estado', true)
                ->where('fecha_inicio', '<=', now())
                ->where('fecha_expiracion', '>', now())
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $this->cupones = [];
        }
    }

    public function realizarPedido(){
        $elementos_carrito = CarritoManagement::obtenerElementosDeCookies();
        $linea_items = [];
        $elementos_carrito = array_filter($elementos_carrito, function ($elemento) {
            return isset($elemento['producto_id']);
        });
        if (empty($elementos_carrito)) {
            return redirect()->back()->withErrors('El carrito está vacío o hay elementos sin producto_id.');
        }
        $orden = new Orden();
        $orden->user_id = auth()->user()->id;
        $orden->total_final = CarritoManagement::calcularTotalFinal($elementos_carrito);
        $orden->metodo_pago = 'efectivo';
        $orden->estado_pago = 'procesando';
        $orden->estado_entrega = 'nuevo';
        $orden->notas = 'Orden Realizada por ' . auth()->user()->name . ' el día y hora: ' . now();
        $orden->save();
        $orden->elementos()->createMany($elementos_carrito);
        if ($orden->save()) {
            CarritoManagement::quitarElementosCookies();
            Notification::make('Orden creada correctamente.')->success()
                ->title(\auth()-> user()->name . ' ha realizado una nueva orden')
                ->body('El pedido se ha creado con éxito.')
                ->actions([
                    ActionNotification::make('View')
                        ->label('Ver Orden')
                        ->url(OrdenResource::getUrl('view', ['record' => $orden])),
                ])
                ->sendToDatabase($orden->user);
            Mail::to($orden->user->email)->send(new PedidoRealizado($orden));
            session(['orden_id' => $orden->id]);
            return redirect()->route('mi_orden', $orden->id);
        } else {
            return redirect()->back()->withErrors('Error al crear el pedido. Por favor, inténtalo de nuevo.');
        }
    }
    public function render()
    {
        return view('livewire.carrito');
    }
}
