<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">Carrito</h1>
        <div class="flex flex-col md:flex-row gap-4">
            <div class="md:w-3/4">
                <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
                    @if ($errors->any())
                        <!-- Muestra todos los errores capturados -->
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                    @if($elementos_carrito)
                        {{--Alerta de información--}}
                        <div class="bg-blue-50 border border-blue-200 text-gray-800 rounded-lg p-4 mb-2" role="alert" tabindex="-1" aria-labelledby="hs-actions-label">
                            <div class="flex">
                                <div class="ms-3">
                                    <div class="mt-2 text-lg text-gray-600 align-middle">
                                        <span class="icon-[ph--info] text-lg text-blue-600"></span> Productos marcados con <span class="font-bold text-red-500">*</span> son productos en oferta.
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                <tr class="@if($item['en_oferta']) rounded-md bg-gray-100 p-2 @endif" wire:key="{{$item['producto_id']}}">
                                    <td class="py-4 ">
                                        <div class="flex items-center">
                                            <img class="h-16 w-16 mr-4"
                                                 src="{{ $item['imagen'] ? url('storage/' . $item['imagen']) : asset('imagen/no-photo.png') }}"
                                                 alt="{{ $item['nombre'] }}">@if($item['en_oferta']) <span class="inline text-red-500 font-bold text-xl"> * </span> @endif
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
                                        <div class="container mx-auto w-full">
                                            <button wire:click="eliminarElemento({{$item['producto_id']}})" class="bg-slate-300 border-2 border-slate-400 rounded-lg px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700 w-full">
                                                <span wire:loading.remove wire:target='eliminarElemento({{$item['producto_id']}})'>Eliminar</span>
                                                <span wire:target="eliminarElemento({{$item['producto_id']}})" wire:loading class="icon-[line-md--loading-loop] h-4 w-4 animate-spin"></span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-4xl font-semibold text-sky-500">No hay items en el carrito</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    @else
                        <div class="mt-4 flex justify-center">
                            <p class="text-center">No hay productos en el carrito.</p>
                        </div>
                    @endif
                         @if (session('productos_eliminados'))
                        <div class="alert alert-warning">
                            <strong>Los siguientes productos han sido eliminados del carrito porque ya no están disponibles:</strong>
                            <ul>
                                Producto
                                 @foreach (session('productos_eliminados') as $producto)
                                     <li>{{ $producto }}</li>
                                 @endforeach
                            </ul>
                        </div>
                        @endif
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

                    <!-- Desglose de precios -->
                    <div class="flex justify-between mb-2">
                        <span>Subtotal (sin ISV)</span>
                        <span>{{ Number::currency($total_original / 1.15, 'LPS') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>ISV (15%)</span>
                        <span>{{ Number::currency($total_original - ($total_original / 1.15), 'LPS') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Envios</span>
                        <span>{{ Number::currency(0, 'LPS') }}</span>
                    </div>

                    {{-- Mostrar descuento por ofertas si existe --}}
                    @php
                        $descuento_ofertas = App\Helpers\CarritoManagement::calcularDescuentoPorOfertas($elementos_carrito);
                    @endphp
                    @if($descuento_ofertas > 0)
                        <div class="flex justify-between mb-2 text-red-500">
                            <span>Descuento por ofertas</span>
                            <span>-{{ Number::currency($descuento_ofertas, 'LPS') }}</span>
                        </div>
                    @endif

                    {{-- Mostrar descuento por cupón si existe --}}
                    @if($descuento_total > $descuento_ofertas)
                        <div class="flex justify-between mb-2 text-red-500">
                            <span>Descuento por cupón</span>
                            <span>-{{ Number::currency($descuento_total - $descuento_ofertas, 'LPS') }}</span>
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

                                <div class="space-y-2">
                                    @if(count($cupones) > 0)
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
                                                    @elseif(!$this->cupónEsAplicable($cupon->id))
                                                        <button class="bg-gray-400 text-black py-1 px-2 rounded-lg cursor-not-allowed whitespace-nowrap border border-black flex-1" disabled>
                                                            No Aplica
                                                        </button>
                                                    @else
                                                        <button wire:click="aplicarCupon({{ $cupon->id }})" class="bg-blue-500 text-white py-1 px-3 rounded-lg">
                                                            Aplicar
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="bg-gray-100 p-4 rounded-lg text-center">
                                            <p class="text-sm text-gray-500">No hay cupones disponibles en este momento.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para reemplazar cupón -->
                    <div x-data="{ open: @entangle('mostrar_modal_cupon') }" x-show="open" class="fixed inset-0 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg p-6 z-10"
                             x-show="open"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90"
                        >
                            <h3 class="text-lg font-semibold">Reemplazar Cupón</h3>
                            <p>Solo puedes escoger un cupón por compra. ¿Deseas reemplazarlo?</p>
                            <label for="nuevo-cupon" class="block mt-4">Selecciona un nuevo cupón:</label>
                            <select id="nuevo-cupon" wire:model="nuevo_cupon_id" class="mt-2 border p-2 rounded">
                                <option value="">-- Selecciona un cupón --</option>
                                @foreach($cupones as $cupon)
                                    @if(!in_array($cupon->id, $cupones_aplicados)) <!-- Excluir el cupón activo -->
                                    <option value="{{ $cupon->id }}">{{ $cupon->codigo }} - {{ $cupon->tipo_descuento === 'porcentaje' ? $cupon->descuento_porcentaje.'% de descuento' : 'Lps. '.$cupon->descuento_dinero.' de descuento' }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="mt-4 flex justify-end">
                                <button @click="open = false" class="bg-gray-300 text-black py-2 px-4 rounded-lg mr-2">Cancelar</button>
                                <button wire:click="confirmarReemplazoCupon" class="bg-blue-500 text-white py-2 px-4 rounded-lg">Aceptar</button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de confirmación de cambios -->
                    <div x-data="{ open: @entangle('modal_confirmacion_pago') }"
                         x-show="open"
                         x-init="@this.on('modal_confirmacion_pago', () => { open = true })"
                         class="fixed inset-0 flex items-center justify-center z-50">
                        <div class="bg-white border-2 border-gray-300 rounded-lg p-6 z-10"
                             x-show="open"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90">
                            <h3 class="text-lg font-semibold">Confirmar Pedido</h3>
                            <p>Se han detectado cambios en tu carrito. ¿Estás seguro de que deseas proceder con el pedido?</p>
                            <div class="mt-4 flex justify-end">
                                <button @click="open = false" class="bg-gray-300 text-black py-2 px-4 rounded-lg mr-2">Cancelar</button>
                                <button wire:click="procesarPedido" class="bg-blue-500 text-white py-2 px-4 rounded-lg">Confirmar</button>
                            </div>
                        </div>
                    </div>


                    <div class="flex justify-between mb-2 mt-2">
                        <span class="font-semibold">Total</span>
                        <span class="font-semibold">{{ Number::currency($total_final, 'LPS') }}</span>
                    </div>
                    @if(auth()->user())
                       @if($elementos_carrito)
                            <button wire:click="realizarPedido"
                                    class="bg-blue-500 text-white py-2 px-4 block rounded-lg mt-4 w-full text-center">
                                <span wire:loading.remove wire:target="realizarPedido">Proceder a pagar</span>
                                <span wire:loading wire:target="realizarPedido" class="icon-[line-md--loading-alt-loop] h-4 w-4 animate-spin"></span>
                            </button>
                        @endif
                    @else
                        <div class="mt-4 flex justify-center">
                            <a wire:navigate
                               class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                               href="/login">
                                Iniciar Sesión
                            </a>
                        </div>
                    @endif
                    @guest
                        <div class="mt-4">
                            <p>Para agregar un cupón, por favor inicie sesión <a href="{{ route('login') }}" class="text-blue-500 underline">aquí</a>.</p>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
