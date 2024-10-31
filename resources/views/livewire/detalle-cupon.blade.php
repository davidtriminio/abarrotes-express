<div class="container-cupones">
    <h1 class="cupon-titulo">TUS CUPONES</h1>

    <div class="cupones-grid">
        @forelse($cupones as $cupon)
            <div class="cupon-card">
                <div class="cupon-header">
                    <h2 class="cupon-descuento">Descuento: {{ $cupon->descuento }}</h2>
                    <p class="cupon-expiracion">Expira: {{ $cupon->fecha_expiracion }}</p>
                    <!-- Agregar restricciones de compra debajo de la fecha de expiración -->
                    <div class="cupon-restricciones">
                        @if($cupon->compra_minima || $cupon->compra_cantidad)
                            @if($cupon->compra_minima)
                                <p class="restriccion">Válido para compras mínimas a L. {{ $cupon->compra_minima }}</p>
                            @endif
                            @if($cupon->compra_cantidad)
                                <p class="restriccion">Válido para la compra de {{ $cupon->compra_cantidad }} productos</p>
                            @endif
                        @else
                            <p class="restriccion">Válido únicamente si el total del carrito es mayor o igual al descuento.</p>
                        @endif
                    </div>
                    <!-- Fin de restricciones de compra -->
                </div>

                <div class="cupon-body">
                    <p class="cupon-codigo">Código: <span>{{ $cupon->codigo }}</span></p>
                    <button class="cupon-usar-btn">Usar</button>
                </div>


            </div>
        @empty
            <h1>Todavía no tienes cupones.</h1>
        @endforelse
    </div>

    <!-- Paginación con divisoria superior -->
    @if ($cupones->isNotEmpty())
        <hr class="my-6 border-gray-300"> <!-- Línea divisoria -->

        <div class="mt-4 text-right">
            <nav aria-label="Page navigation">
                <ul class="flex justify-center space-x-2">
                    @if ($cupones->onFirstPage())
                        <li class="disabled">
                            <span class="px-4 py-2 bg-gray-300 text-gray-500 cursor-not-allowed">Anterior</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $cupones->previousPageUrl() }}"
                               class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">Anterior</a>
                        </li>
                    @endif

                    @for ($i = 1; $i <= $cupones->lastPage(); $i++)
                        <li>
                            @if ($i == $cupones->currentPage())
                                <span class="px-4 py-2 bg-blue-500 text-white rounded">{{ $i }}</span>
                            @else
                                <a href="{{ $cupones->url($i) }}"
                                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-blue-500 hover:text-white transition duration-300">{{ $i }}</a>
                            @endif
                        </li>
                    @endfor

                    @if ($cupones->hasMorePages())
                        <li>
                            <a href="{{ $cupones->nextPageUrl() }}"
                               class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">Siguiente</a>
                        </li>
                    @else
                        <li class="disabled">
                            <span class="px-4 py-2 bg-gray-300 text-gray-500 cursor-not-allowed">Siguiente</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @endif
</div>
