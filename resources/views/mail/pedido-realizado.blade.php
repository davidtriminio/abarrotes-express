<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Compra</title>
    <style>.sm\\:-mx-6 {
            margin-left: -1.5rem;
            margin-right: -1.5rem
        }

        .lg\\:-mx-8 {
            margin-left: -2rem;
            margin-right: -2rem
        }

        .sm\\:px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem
        }

        .lg\\:px-8 {
            padding-left: 2rem;
            padding-right: 2rem
        }

        .sm\\:rounded-lg {
            border-radius: 0.5rem
        }

        .divide-y > :not([hidden]) ~ :not([hidden]) {
            border-top-width: 1px
        }

        .divide-gray-200 > :not([hidden]) ~ :not([hidden]) {
            border-color: #e5e7eb
        }

        .hover\\:bg-gray-100:hover {
            background-color: #f3f4f6
        }</style>
</head>
<body style="background-color:#f3f4f6; font-family:Arial, sans-serif; margin:0; padding:0" bgcolor="#f3f4f6">
<div class="container"
     style="background-color:rgba(105, 156, 238, 0.1); border-radius:0.5rem; box-shadow:0 4px 6px rgba(0, 0, 0, 0.1); margin:2rem auto; max-width:640px; padding:2rem"
     bgcolor="rgba(105, 156, 238, 0.1)">
    <!-- Encabezado -->
    <div class="header text-center"
         style="text-align:center; background-color:rgba(59, 130, 246, 0.1); border-radius:0.5rem; margin-bottom:2rem; padding:1rem"
         align="center" bgcolor="rgba(59, 130, 246, 0.1)">
        <img src="https://i.ibb.co/48StGsQ/logo1.jpg" alt="logo1" width="50px">
    </div>

    <!-- Saludo -->
    <h1 style="color:#3B82F6; font-size:1.5rem; font-weight:bold; margin-bottom:1rem">
        Hola {{auth()->user()->name}},</h1>
    <p style="color:#4B5563; margin-bottom:1.5rem">Gracias por tu compra. Aquí tienes un resumen de tu pedido:</p>

    <!-- Resumen de compra -->
    <div class="summary" style="background-color:#f9fafb; border-radius:1.6rem; margin-bottom:1.5rem; padding:1rem"
         bgcolor="#f9fafb">
        <h2 style="color:#3B82F6; font-size:1.25rem; font-weight:600; margin-bottom:1rem">Resumen de Compra</h2>
        <table style="border-collapse:collapse; width:100%" width="100%">
            <thead>
            <tr>
                <th style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#4B5563; text-align:left"
                    align="left">Producto
                </th>
                <th class="text-center"
                    style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#4B5563; text-align:center"
                    align="center">Cantidad
                </th>
                <th class="text-right"
                    style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#4B5563; text-align:right"
                    align="right">Precio
                </th>
                <th class="text-right"
                    style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#4B5563; text-align:right"
                    align="right">Total
                </th>
            </tr>
            </thead>
            <tbody>
            @if(isset($elementos_carrito) && count($elementos_carrito) > 0)
            @foreach($elementos_carrito as $elemento)
            <tr>
                <td class="product-info"
                    style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#1f2937; align-items:center; display:flex">
                    <span>{{$elemento['nombre']}}</span>
                </td>
                <td class="text-center"
                    style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#1f2937; text-align:center"
                    align="center">{{$elemento['cantidad']}}</td>
                <td class="text-right"
                    style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#1f2937; text-align:right"
                    align="right">{{Number::currency($elemento['monto_unitario'], 'LPS')}}</td>
                <td class="text-right"
                    style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#1f2937; text-align:right"
                    align="right">{{Number::currency($elemento['monto_total'], 'LPS')}}</td>
            </tr>
            @endforeach
            @else
            <p style="color:#4B5563; margin-bottom:1.5rem">No hay elementos en el carrito.
                @endif
            </p>
            <tr class="font-bold" style="font-weight:bold">
                <td colspan="3" style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#1f2937">Total a
                    pagar
                </td>
                <td class="text-end"
                    style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#1f2937; text-align:right"
                    align="right">{{Number::currency($orden->total_final, 'LPS')}}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Datos del usuario -->
    <div class="user-info" style="margin-bottom:1.5rem">
        <h2 style="color:#3B82F6; font-size:1.25rem; font-weight:600; margin-bottom:0.5rem">Tus Datos</h2>
        <p class="text-gray" style="color:#4B5563; margin-bottom:1.5rem">Nombre de
            usuario: {{auth()->user()->name}}</p>
        <p class="text-gray" style="color:#4B5563; margin-bottom:1.5rem">Correo
            electrónico: {{auth()->user()->email}}</p>
    </div>

    <!-- Sección de cuentas de pago -->
    <div class="payment-info" style="border-radius:2rem; margin-bottom:1.5rem; padding:1rem; text-align:center">
        <h2 style="color:#3B82F6; font-size:1.25rem; font-weight:600; margin-bottom:0.5rem">Cuentas autorizadas para
            Realizar el Pago</h2>
        <div class="flex flex-col"
             style="display:flex; flex-direction:column; background-color:rgba(105, 156, 238, 0.1); border-radius:0.5rem; padding:2rem; margin: auto;"
             bgcolor="rgba(105, 156, 238, 0.1)">
            <!-- Tabla de cuentas centrada -->
            <table class="min-w-full w-full divide-y divide-gray-200"
                   style="margin:auto; border-collapse:collapse; width:80%;">
                <thead class="bg-gray-50" style="background-color:#f9fafb" bgcolor="#f9fafb">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider"
                        style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#6b7280; text-align:left; padding-left:1.5rem; padding-right:1.5rem; padding-bottom:0.75rem; padding-top:0.75rem; font-size:1rem; line-height:1rem; font-weight:500; text-transform:uppercase; letter-spacing:0.05em"
                        align="left">Banco
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider"
                        style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#6b7280; text-align:left; padding-left:1.5rem; padding-right:1.5rem; padding-bottom:0.75rem; padding-top:0.75rem; font-size:1rem; line-height:1rem; font-weight:500; text-transform:uppercase; letter-spacing:0.05em"
                        align="left">Número de Cuenta
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200"
                       style="background-color:rgba(255, 255, 255, 0.65)"
                       bgcolor="rgba(255, 255, 255, 0.65)">
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"
                        style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#1f2937; padding-left:1.5rem; padding-right:1.5rem; font-weight:500; white-space:nowrap; font-size:0.875rem; line-height:1.25rem">
                        Banco Atlántida
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"
                        style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#1f2937; padding-left:1.5rem; padding-right:1.5rem; white-space:nowrap; font-size:0.875rem; line-height:1.25rem">
                        100-2221-25215
                    </td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"
                        style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#1f2937; padding-left:1.5rem; padding-right:1.5rem; font-weight:500; white-space:nowrap; font-size:0.875rem; line-height:1.25rem">
                        Banco Azteca
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"
                        style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#1f2937; padding-left:1.5rem; padding-right:1.5rem; white-space:nowrap; font-size:0.875rem; line-height:1.25rem">
                        015-2536-45
                    </td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"
                        style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#1f2937; padding-left:1.5rem; padding-right:1.5rem; font-weight:500; white-space:nowrap; font-size:0.875rem; line-height:1.25rem">
                        Banpais
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"
                        style="border-bottom:1px solid #e5e7eb; padding:0.5rem; color:#1f2937; padding-left:1.5rem; padding-right:1.5rem; white-space:nowrap; font-size:0.875rem; line-height:1.25rem">
                        345-785-0000256-7
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

</div>

<!-- Pie de página -->
<div class="footer"
     style="border-top:1px solid #e5e7eb; color:#9CA3AF; font-size:0.875rem; margin-top:2rem; padding-top:1rem; text-align:center"
     align="center">
    <p style="color:#4B5563; margin-bottom:1.5rem">Puede responder a este correo con el comprobante de pago o
        enviarlo al número de
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewbox="0 0 24 24">
            <path fill="#059669"
                  d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21c5.46 0 9.91-4.45 9.91-9.91c0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.23 8.23 0 0 1 2.41 5.83c0 4.54-3.7 8.23-8.24 8.23c-1.48 0-2.93-.39-4.19-1.15l-.3-.17l-3.12.82l.83-3.04l-.2-.32a8.2 8.2 0 0 1-1.26-4.38c.01-4.54 3.7-8.24 8.25-8.24M8.53 7.33c-.16 0-.43.06-.66.31c-.22.25-.87.86-.87 2.07c0 1.22.89 2.39 1 2.56c.14.17 1.76 2.67 4.25 3.73c.59.27 1.05.42 1.41.53c.59.19 1.13.16 1.56.1c.48-.07 1.46-.6 1.67-1.18s.21-1.07.15-1.18c-.07-.1-.23-.16-.48-.27c-.25-.14-1.47-.74-1.69-.82c-.23-.08-.37-.12-.56.12c-.16.25-.64.81-.78.97c-.15.17-.29.19-.53.07c-.26-.13-1.06-.39-2-1.23c-.74-.66-1.23-1.47-1.38-1.72c-.12-.24-.01-.39.11-.5c.11-.11.27-.29.37-.44c.13-.14.17-.25.25-.41c.08-.17.04-.31-.02-.43c-.06-.11-.56-1.35-.77-1.84c-.2-.48-.4-.42-.56-.43c-.14 0-.3-.01-.47-.01"></path>
        </svg>
        Whatsapp:+504 3333 9999
    </p>
    <p style="color:#4B5563; margin-bottom:1.5rem">© 2024 {{ config('app.name') }}. Todos los derechos
        reservados.</p>
</div>
</div>
</body>
</html>
