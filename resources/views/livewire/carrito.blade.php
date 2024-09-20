<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">Carrito</h1>
        <div class="flex flex-col md:flex-row gap-4">
            <div class="md:w-3/4">
                <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
                    <table class="w-full">
                        <thead>
                        <tr>
                            <th class="text-left font-semibold">Producto</th>
                            <th class="text-left font-semibold">Precio</th>
                            <th class="text-left font-semibold">Cantidad</th>
                            <th class="text-left font-semibold">Total</th>
                            <th class="text-left font-semibold">Quitar</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($elementos_carrito as $item)
                            <tr wire:key="{{$item['producto_id']}}">
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <img class="h-16 w-16 mr-4" src="{{ url('storage', $item['imagen']) }}" alt="{{$item['nombre']}}">
                                        <span class="font-semibold">{{ $item['nombre'] }}</span>
                                    </div>
                                </td>
                                <td class="py-4">{{ Number::currency($item['monto_unitario'], 'LPS') }}</td>
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <button wire:click="decrementarCantidad({{$item['producto_id']}})" class="border rounded-md py-2 px-4 mr-2">-</button>
                                        <span class="text-center w-8">{{$item['cantidad']}}</span>
                                        <button wire:click="incrementarCantidad({{$item['producto_id']}})" class="border rounded-md py-2 px-4 ml-2">+</button>
                                    </div>
                                </td>
                                <td class="py-4">{{ Number::currency($item['monto_total'], 'LPS') }}</td>
                                <td>
                                    <button wire:click="eliminarElemento({{$item['producto_id']}})" class="bg-slate-300 border-2 border-slate-400 rounded-lg px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700">
                                        <span wire:loading.remove wire:target='eliminarElemento({{$item['producto_id']}})'>Eliminar</span>
                                        <span wire:target="eliminarElemento({{$item['producto_id']}})" wire:loading>Eliminando...</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-4xl font-semibold text-sky-500">No hay items en el carrito</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="md:w-1/3">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <!-- Título de detalles de compra -->
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">Detalles de compra</h2>
                        @auth
                            <!-- Botón para abrir el menú de cupones -->
                            <button wire:click="toggleMenuCupones" class="bg-blue-500 text-white py-2 px-4 rounded-lg">
                                + Cupón
                            </button>
                        @endauth
                    </div>

                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span>{{ Number::currency($total_original, 'LPS') }}</span> <!-- Total sin aplicar cupones -->
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Impuestos</span>
                        <span>{{ Number::currency(0, 'LPS') }}</span> <!-- Impuestos -->
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Envios</span>
                        <span>{{ Number::currency(0, 'LPS') }}</span>
                    </div>
                    <!-- Descuento aplicado -->
                    @if($descuento_total > 0)
                        <div class="flex justify-between mb-2 text-green-600">
                            <span>Descuento aplicado</span>
                            <span>-{{ Number::currency($descuento_total, 'LPS') }}</span> <!-- Mostrar descuento total -->
                        </div>
                    @endif

                    <!-- Línea separadora -->
                    <hr class="my-4 border-gray-300">

                    <!-- Menú desplegable lateral -->
                    <div x-data="{ open: @entangle('mostrar_menu_cupones') }">
                        <div x-show="open" class="fixed inset-0 flex z-50">
                            <!-- Capa oscura -->
                            <div x-show="open" @click="open = false" x-transition class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>

                            <!-- Menú lateral -->
                            <div x-show="open" x-transition class="relative w-72 bg-white h-full shadow-xl p-4 overflow-y-auto">
                                <!-- Botón de cierre -->
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold">Cupones Disponibles</h3>
                                    <button @click="open = false" class="text-gray-600 hover:text-gray-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Lista de cupones -->
                                <div class="space-y-2">
                                    @foreach($cupones as $cupon)
                                        <div class="bg-gray-100 p-4 rounded-lg flex justify-between items-center">
                                            <div>
                                                <p class="text-sm font-semibold">{{ $cupon->codigo }}</p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $cupon->tipo_descuento === 'porcentaje' ? $cupon->descuento_porcentaje.'% de descuento' : 'Lps. '.$cupon->descuento_dinero.' de descuento' }}
                                                </p>
                                            </div>
                                            <div class="ml-4">
                                                @if(in_array($cupon->id, $cupones_aplicados))
                                                    <button wire:click="retirarCupon({{ $cupon->id }})" class="bg-red-500 text-white py-1 px-3 rounded-lg">
                                                        Retirar
                                                    </button>
                                                @else
                                                    <button wire:click="aplicarCupon({{ $cupon->id }})" class="bg-blue-500 text-white py-1 px-3 rounded-lg">
                                                        Aplicar
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Final después del cupón -->
                    <div class="flex justify-between mb-2 mt-2">
                        <span class="font-semibold">Total</span>
                        <span class="font-semibold">{{ Number::currency($total_final, 'LPS') }}</span> <!-- Total con el cupón aplicado -->
                    </div>
                    @if($elementos_carrito)
                        <a href="/pedido" class="bg-blue-500 text-white py-2 px-4 block rounded-lg mt-4 w-full text-center">
                            Proceder al pago
                        </a>
                    @endif

                    @guest
                        <div class="mt-4">
                            <p>Para agregar un cupón, por favor inicie sesión <a href="/login" class="text-blue-500 underline">aquí</a>.</p>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>