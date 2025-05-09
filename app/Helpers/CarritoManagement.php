<?php

namespace App\Helpers;

use App\Models\Cupon;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CarritoManagement
{
    /*Agregar elementos al carrito*/
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

        $producto = Producto::where('id', $producto_id)->first(['id', 'nombre', 'precio', 'imagenes', 'porcentaje_oferta', 'en_oferta', 'cantidad_disponible']);

        if ($producto) {
            if ($elemento_existente !== null) {
                // Validar que no exceda la cantidad disponible
                if ($elementos_carrito[$elemento_existente]['cantidad'] < $producto->cantidad_disponible) {
                    $elementos_carrito[$elemento_existente]['cantidad']++;
                    $elementos_carrito[$elemento_existente]['monto_total'] = self::calcularMontoTotal(
                        $elementos_carrito[$elemento_existente]['monto_unitario'],
                        $elementos_carrito[$elemento_existente]['porcentaje_oferta'],
                        $elementos_carrito[$elemento_existente]['cantidad']
                    );
                } else {
                    return "Se ha llegado al límite de cantidad de productos en inventario";
                }
            } else {
                if ($producto->cantidad_disponible > 0) {
                    $precio_con_descuento = $producto->en_oferta
                        ? self::calcularPrecioConDescuento($producto->precio, $producto->porcentaje_oferta)
                        : $producto->precio;

                    $elementos_carrito[] = [
                        'producto_id' => $producto_id,
                        'nombre' => $producto->nombre,
                        'imagen' => $producto->imagenes[0] ?? null,
                        'cantidad' => 1,
                        'porcentaje_oferta' => $producto->porcentaje_oferta ?? 0,
                        'precio_sin_oferta' => $producto->precio,
                        'monto_unitario' => $precio_con_descuento,
                        'monto_total' => $precio_con_descuento,
                        'en_oferta' => $producto->en_oferta,
                    ];
                } else {
                    return "Este producto no tiene unidades disponibles.";
                }
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

        $producto = Producto::where('id', $producto_id)->first(['id', 'nombre', 'precio', 'imagenes', 'porcentaje_oferta', 'en_oferta', 'cantidad_disponible']);

        if ($producto) {
            if ($elementos_existentes !== null) {
                /* Si el producto ya existe en el carrito */
                $cantidad_actual = $elementos_carrito[$elementos_existentes]['cantidad'];
                $nueva_cantidad = $cantidad_actual + $cantidad;

                // Validar que no exceda el inventario disponible
                if ($nueva_cantidad > $producto->cantidad_disponible) {
                    return "Se ha llegado al límite de cantidad de productos en inventario.";
                }

                // Actualizar la cantidad y el monto total
                $elementos_carrito[$elementos_existentes]['cantidad'] = $nueva_cantidad;
                $elementos_carrito[$elementos_existentes]['monto_total'] = $nueva_cantidad * $elementos_carrito[$elementos_existentes]['monto_unitario'];
            } else {
                /* Si el producto no está en el carrito, agregarlo */
                if ($cantidad > $producto->cantidad_disponible) {
                    return "No se pueden agregar más productos de los disponibles en inventario.";
                }

                $precio_con_descuento = $producto->en_oferta
                    ? self::calcularPrecioConDescuento($producto->precio, $producto->porcentaje_oferta)
                    : $producto->precio;

                $elementos_carrito[] = [
                    'producto_id' => $producto_id,
                    'nombre' => $producto->nombre,
                    'imagen' => isset($producto->imagenes[0]) && !empty($producto->imagenes[0])
                        ? $producto->imagenes[0] : null,
                    'cantidad' => $cantidad,
                    'porcentaje_oferta' => $producto->porcentaje_oferta ?? 0,
                    'precio_sin_oferta' => $producto->precio,
                    'monto_unitario' => $precio_con_descuento,
                    'monto_total' => $precio_con_descuento * $cantidad,
                    'en_oferta' => $producto->en_oferta,
                ];
            }

            // Actualizar cookies
            self::agregarElementoCookies($elementos_carrito);
            return count($elementos_carrito);
        }

        return 'Producto no encontrado';
    }


    static public function agregarDescuentoCookies($descuento_total, $cupones_aplicados, $nuevo_cupon_id)
    {
        if (Auth::check()) {
            Cookie::queue('descuento_total', json_encode($descuento_total), 60 * 24 * 30);
            Cookie::queue('cupones_aplicados', json_encode($cupones_aplicados), 60 * 24 * 30);
            if ($nuevo_cupon_id) {
                Cookie::queue('nuevo_cupon_id', json_encode($nuevo_cupon_id), 60 * 24 * 30);
            }
        }
    }

    static public function obtenerDescuentoDeCookies()
    {
        if (Auth::check()) {
            $descuento_total = json_decode(Cookie::get('descuento_total'), true);
            $cupones_aplicados = json_decode(Cookie::get('cupones_aplicados'), true);

            if (!empty($cupones_aplicados)) {
                foreach ($cupones_aplicados as $key => $cupon_id) {
                    $cupon = Cupon::find($cupon_id);
                    if ($cupon) {
                        $fecha_actual = now();

                        if ($fecha_actual < $cupon->fecha_inicio || $fecha_actual > $cupon->fecha_expiracion) {
                            unset($cupones_aplicados[$key]);
                            $descuento_total = 0;
                        }
                    }
                }
            }

            return [
                'descuento_total' => $descuento_total ? $descuento_total : 0,
                'cupones_aplicados' => $cupones_aplicados ? $cupones_aplicados : [],
            ];
        }

        return [
            'descuento_total' => 0,
            'cupones_aplicados' => [],
        ];
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
        $producto_eliminado = false;

        foreach ($elementos_carrito as $key => $item) {
            if ($item['producto_id'] == $producto_id) {
                unset($elementos_carrito[$key]);
                $producto_eliminado = true;
                break;
            }
        }

        if ($producto_eliminado) {
            self::quitarCuponesYDescuentos();
        }

        self::agregarElementoCookies($elementos_carrito);
        return $elementos_carrito;
    }

    static public function quitarCuponesYDescuentos()
    {
        Cookie::queue(Cookie::forget('descuento_total'));
        Cookie::queue(Cookie::forget('cupones_aplicados'));
        Cookie::queue(Cookie::forget('nuevo_cupon_id'));
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

                if ($producto) {
                    if ($elementos_carrito[$key]['cantidad'] < $producto->cantidad_disponible) {
                        $elementos_carrito[$key]['cantidad']++;
                        $elementos_carrito[$key]['monto_total'] = $elementos_carrito[$key]['cantidad'] *
                            $elementos_carrito[$key]['monto_unitario'];
                    } else {
                        return "Se ha llegado al límite de cantidad de productos en inventario";
                    }
                } else {
                    return "Producto no encontrado";
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

    /*Validar elementos del carrito*/
    static public function verificarProductosEnCarrito($elementos_carrito)
    {
        $elementos_validos = [];
        $productos_eliminados = [];

        foreach ($elementos_carrito as $key => $elemento) {
            $producto = Producto::find($elemento['producto_id']);
            if ($producto) {
                $elementos_validos[] = $elemento;
            } else {
                $productos_eliminados[] = $elemento['nombre'] ?? 'Producto desconocido';
                unset($elementos_carrito[$key]);
            }
        }

        // Actualizar las cookies y elimiinar los productos no encontrados
        CarritoManagement::agregarElementoCookies($elementos_carrito);

        if (!empty($productos_eliminados)) {
            session()->flash('productos_eliminados', $productos_eliminados);
        }
        return $elementos_validos;
    }

    static public function calcularSubTotalSinDescuentos($elementos)
    {
        $subtotal = 0;
        foreach ($elementos as $item) {
            $subtotal += $item['precio_sin_oferta'] * $item['cantidad'];
        }
        return $subtotal;
    }


    static public function calcularDescuentoPorOfertas($elementos)
    {
        $descuento = 0;
        foreach ($elementos as $item) {
            $producto = Producto::find($item['producto_id']);
            if ($producto && $producto->en_oferta) {
                $precio_normal = $producto->precio;
                $precio_oferta = self::calcularPrecioConDescuento($precio_normal, $producto->porcentaje_oferta);
                $descuento += ($precio_normal - $precio_oferta) * $item['cantidad'];
            }
        }
        return $descuento;
    }

}



