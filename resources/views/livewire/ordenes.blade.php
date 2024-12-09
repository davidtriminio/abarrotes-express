<div class="space-y-4">
    {{-- Wrap everything in a single root div with spacing utility --}}
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" id="ordenDetalles">
        <div>
            <h2 class="text-4xl font-bold text-gray-800 mb-4 text-center">Detalles de la Orden</h2>
            @if(in_array($orden->estado_entrega, ['nuevo', 'procesado', 'entregado']))
                <button
                    wire:click="iniciarDevolucion"
                    class="mt-4 bg-red-500 text-white font-bold py-2 px-4 rounded hover:bg-red-700"
                >
                    Devolución
                </button>
            @endif
        </div>

        <div id="orden" class="bg-white shadow-xl rounded-lg p-8 border border-gray-300 mt-4">
            <!-- User Information and Invoice Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Your existing user info and invoice details sections -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-300 shadow-md transition-transform transform hover:scale-105">
                    <h3 class="text-xl font-semibold text-gray-700 border-b-2 border-blue-500 pb-2">Información del Usuario</h3>
                    <p class="mt-2"><strong>Nombre de Usuario:</strong> <span class="text-gray-600">{{ $orden->user->name }}</span></p>
                    <p class="mt-2"><strong>Correo:</strong> <span class="text-gray-600">{{ $orden->user->email }}</span></p>
                </div>

                <div class="bg-gray-110 p-6 rounded-lg border border-gray-300 shadow-md transition-transform transform hover:scale-105">
                    <h3 class="text-xl font-semibold text-gray-700 border-b-2 border-blue-500 pb-2">Detalles de la Factura</h3>
                    <p class="mt-2"><strong>Método de Pago:</strong> <span class="text-gray-600">{{ strpos($orden->metodo_pago, 'par') !== false ? 'Pago a recibir' : $orden->metodo_pago }}</span></p>
                    <p class="mt-2"><strong>Estado de Pago:</strong> <span class="text-gray-600">{{ ucfirst($orden->estado_pago) }}</span></p>
                    <p class="mt-2"><strong>Estado de Entrega:</strong> <span class="text-gray-600">{{ ucfirst($orden->estado_entrega) }}</span></p>
                </div>
            </div>

            <!-- Products List -->
            <div class="mt-8">
    <h3 class="text-2xl font-bold text-gray-800 border-b-2 border-blue-500 pb-2">
        Listado de los Productos de la orden
    </h3>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Cantidad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Precio Unitario</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($orden->elementos as $elemento)
                    <tr>
                        <td class="px-6 py-4 ">
                            <div class="text-sm text-gray-700">{{ $elemento->producto->nombre }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">{{ $elemento->cantidad }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">L. {{ $elemento->monto_unitario }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-sm text-gray-500 text-center">No hay productos registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- Order Summary -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-700 border-b-2 border-blue-500 pb-2">Resumen de la Orden</h3>
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-300 mt-2 shadow-md">
                    <p><strong>Subtotal:</strong> <span class="text-gray-600">{{ number_format($orden->sub_total, 2) }}</span></p>
                    <p><strong>Total Final:</strong> <span class="text-gray-600">{{ number_format($orden->total_final, 2) }}</span></p>
                    <p><strong>Costos de Envío:</strong> <span class="text-gray-600">{{ number_format($orden->costos_envio, 2) }}</span></p>
                    <p><strong>Fecha de Entrega:</strong> <span class="text-gray-600">{{ $orden->fecha_entrega ? Carbon\Carbon::parse($orden->fecha_entrega)->format('d/m/Y') : 'N/A' }}</span></p>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-700 border-b-2 border-blue-500 pb-2">Notas Adicionales</h3>
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-300 mt-2 shadow-md">
                    <p class="text-gray-600">{{ $orden->notas }}</p>
                </div>
            </div>
        </div>

        <button onclick="printTable()" class="mt-4 bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
            Imprimir Orden
        </button>
    </div>

    @if($confirmingReturn)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Confirmar Devolución</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            ¿Está seguro que desea procesar la devolución de esta orden?
                        </p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button
                            wire:click="procesarDevolucion"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300"
                        >
                            Confirmar Devolución
                        </button>
                        <button
                            wire:click="$set('confirmingReturn', false)"
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 ml-3"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function printTable() {
            var printContents = document.getElementById('orden').innerHTML;
            var originalContents = document.body.innerHTML;

            // Cambiar el contenido del body al contenido que se quiere imprimir
            document.body.innerHTML = printContents;

            // Usar setTimeout para esperar 3 segundos antes de imprimir
            setTimeout(function() {
                window.print();
                // Restaurar el contenido original del body después de imprimir
                document.body.innerHTML = originalContents;
            }, 100); // 3000 milisegundos = 3 segundos
        }
    </script>
</div>






