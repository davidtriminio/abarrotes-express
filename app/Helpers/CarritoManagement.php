<?php

namespace App\Helpers;

use App\Models\Producto;
use Illuminate\Support\Facades\Cookie;

class CarritoManagement
{
    /*Agregar elementos al carrito*/
    static public function agregarElementoAlCarrito($producto_id)
    {
        $elementos_carrito = self::obtenerElementosDeCookies();
        $elemento_existente = null;

        foreach ($elementos_carrito as $key => $item) {
            if ($item['producto_id'] == $producto_id) {
                $elemento_existente = $key;
                break;
            }
        }

        $producto = Producto::where('id', $producto_id)->first(['id', 'nombre', 'precio', 'imagenes', 'porcentaje_oferta', 'en_oferta']);

        if ($producto) {
            if ($elemento_existente !== null) {
                $elementos_carrito[$elemento_existente]['cantidad']++;
                $elementos_carrito[$elemento_existente]['monto_total'] = self::calcularMontoTotal(
                    $elementos_carrito[$elemento_existente]['monto_unitario'],
                    $elementos_carrito[$elemento_existente]['porcentaje_oferta'],
                    $elementos_carrito[$elemento_existente]['cantidad']
                );
            } else {
                $precio_con_descuento = $producto->en_oferta
                    ? self::calcularPrecioConDescuento($producto->precio, $producto->porcentaje_oferta)
                    : $producto->precio;

                $elementos_carrito[] = [
                    'producto_id' => $producto_id,
                    'nombre' => $producto->nombre,
                    'imagen' => $producto->imagenes[0] ?? 'default_image.jpg',
                    'cantidad' => 1,
                    'porcentaje_oferta' => $producto->porcentaje_oferta ?? 0,
                    'monto_unitario' => $precio_con_descuento,
                    'monto_total' => $precio_con_descuento,
                    'en_oferta' => $producto->en_oferta,
                ];
            }
            self::agregarElementoCookies($elementos_carrito);
            return count($elementos_carrito);
        }
        return 'Producto no encontrado';
    }

    static public function agregarElementosAlCarritoConCantidad($producto_id, $cantidad = 1)
    {
        $elementos_carrito = self::obtenerElementosDeCookies();
        $elementos_existentes = null;

        foreach ($elementos_carrito as $key => $item) {
            if ($item['producto_id'] == $producto_id) {
                $elementos_existentes = $key;
                break;
            }
        }

        /* Si ya existe, solo actualiza la cantidad */
        if ($elementos_existentes !== null) {
            $elementos_carrito[$elementos_existentes]['cantidad'] += $cantidad; // Aumenta la cantidad
            $elementos_carrito[$elementos_existentes]['monto_total'] = self::calcularMontoTotal(
                $elementos_carrito[$elementos_existentes]['monto_unitario'],
                $elementos_carrito[$elementos_existentes]['porcentaje_oferta'],
                $elementos_carrito[$elementos_existentes]['cantidad']
            );
        } else {
            /* Si no existe, agrega el producto al carrito */
            $producto = Producto::where('id', $producto_id)->first(['id', 'nombre', 'precio', 'imagenes', 'porcentaje_oferta', 'en_oferta']);
            if ($producto) {
                $precio_con_descuento = $producto->en_oferta
                    ? self::calcularPrecioConDescuento($producto->precio, $producto->porcentaje_oferta)
                    : $producto->precio;
                $elementos_carrito[] = [
                    'producto_id' => $producto_id,
                    'nombre' => $producto->nombre,
                    'imagen' => $producto->imagenes[0] ?? 'default_image.jpg',
                    'cantidad' => $cantidad, // Usar la cantidad pasada
                    'porcentaje_oferta' => $producto->porcentaje_oferta,
                    'monto_unitario' => $precio_con_descuento,
                    'monto_total' => $precio_con_descuento * $cantidad, // Monto total basado en cantidad
                    'en_oferta' => $producto->en_oferta,
                ];
            }
        }
        self::agregarElementoCookies($elementos_carrito);
        return count($elementos_carrito);
    }

    static public function calcularPrecioConDescuento($precio, $porcentaje_oferta)
    {
        if (!is_null($porcentaje_oferta) && $porcentaje_oferta > 0) {
            return $precio - ($precio * ($porcentaje_oferta / 100));
        }
        return $precio;
    }

    static public function calcularMontoTotal($precio_unitario, $porcentaje_oferta, $cantidad)
    {
        return $cantidad * $precio_unitario;
    }

    static public function quitarElementosCarrito($producto_id)
    {
        $elementos_carrito = self::obtenerElementosDeCookies();

        foreach ($elementos_carrito as $key => $item) {
            if ($item['producto_id'] == $producto_id) {
                unset($elementos_carrito[$key]);
            }
        }
        self::agregarElementoCookies($elementos_carrito);
        return $elementos_carrito;
    }

    /*Agregar elementos a cookies*/
    static public function agregarElementoCookies($elementos_carrito)
    {
        Cookie::queue('elementos_carrito', json_encode($elementos_carrito), 60 * 24 * 30);
    }

    static public function quitarElementosCookies()
    {
        Cookie::queue(Cookie::forget('elementos_carrito'));
    }

    /*Obtener elementos de cookies*/
    static public function obtenerElementosDeCookies()
    {
        $elementos_carrito = json_decode(Cookie::get('elementos_carrito'), true);
        if (!$elementos_carrito) {
            $elementos_carrito = [];
        }
        return $elementos_carrito;
    }

    /*Incrementar cantidad de un item en el carrito*/
    static public function incrementarCantidadElementosCarrito($producto_id)
    {
        $elementos_carrito = self::obtenerElementosDeCookies();

        foreach ($elementos_carrito as $key => $item) {
            if ($item['producto_id'] == $producto_id) {
                $producto = Producto::find($producto_id);
                if ($producto && $elementos_carrito[$key]['cantidad'] < $producto->cantidad_disponible) {
                    $elementos_carrito[$key]['cantidad']++;
                    if (isset($elementos_carrito[$key]['cantidad_disponible'])) {
                        $elementos_carrito[$key]['cantidad_disponible']--;
                    }
                    $elementos_carrito[$key]['monto_total'] = $elementos_carrito[$key]['cantidad'] *
                        $elementos_carrito[$key]['monto_unitario'];
                } else {
                    return 'Cantidad excede la disponible';
                }
            }
        }

        self::agregarElementoCookies($elementos_carrito);
        return $elementos_carrito;
    }

    static public function decrementarCantidadElementosCarrito($producto_id)
    {
        $elementos_carrito = self::obtenerElementosDeCookies();
        foreach ($elementos_carrito as $key => $item) {
            if ($item['producto_id'] == $producto_id) {
                if ($elementos_carrito[$key]['cantidad'] > 1) {
                    $elementos_carrito[$key]['cantidad']--;
                    if (isset($elementos_carrito[$key]['cantidad_disponible'])) {
                        $elementos_carrito[$key]['cantidad_disponible']++;
                    }
                    $elementos_carrito[$key]['monto_total'] = $elementos_carrito[$key]['cantidad'] *
                        $elementos_carrito[$key]['monto_unitario'];
                }
            }
        }
        self::agregarElementoCookies($elementos_carrito);
        return $elementos_carrito;
    }

    static public function calcularTotalFinal($elementos)
    {
        return array_sum(array_column($elementos, 'monto_total'));
    }
}
