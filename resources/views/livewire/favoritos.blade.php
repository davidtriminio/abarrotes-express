<div class="container mx-auto py-8">
    <!-- Título centrado -->
    <h1 class="text-4xl font-bold text-center mb-6 text-primary">MIS FAVORITOS</h1>

    <!-- Contenedor de tabla con productos -->
    <div class="bg-white rounded-lg shadow-md p-6 overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-3"> <!-- Tabla con espaciado entre filas -->
            <thead>
            <tr class="border-b-4 border-gray-400"> <!-- Línea divisoria más gruesa entre el encabezado y el cuerpo -->
                <th class="text-center font-semibold py-2 border-b-2 border-gray-300">Producto</th>
                <th class="text-center font-semibold py-2 border-b-2 border-gray-300">Precio</th>
                <th class="text-center font-semibold py-2 border-b-2 border-gray-300">Añadir</th>
            </tr>
            </thead>
            <tbody>
            @forelse($favoritos as $favorito)
                <tr class="border-b border-gray-200 hover:bg-gray-100 transition duration-300"> <!-- Borde inferior y efecto hover -->
                    <!-- Columna de producto con imagen y nombre -->
                    <td class="py-4">
                        <div class="flex items-center">
                            <img class="h-16 w-16 mr-4 rounded-md object-cover"
                                 src="{{ isset($favorito->producto->imagenes[0]) ? url('storage', $favorito->producto->imagenes[0]) : asset('imagen/no-photo.jpg') }}"
                                 alt="{{ $favorito->producto->nombre }}">
                            <span class="font-semibold text-primary">{{ $favorito->producto->nombre }}</span>
                        </div>
                    </td>

                    <!-- Columna de precio -->
                    <td class="py-4 text-center">
                        <span class="text-lg font-bold text-primary">L. {{ $favorito->producto->precio }}</span>
                    </td>

                    <!-- Columna de botón para agregar al carrito -->
                    <td class="py-4 text-center">
                        <button wire:click="agregarAlCarrito({{ $favorito->producto->id }})"
                                class="bg-blue-500 text-white py-2 px-4 rounded-md transition duration-300 hover:bg-blue-600">
                            <span wire:loading.remove wire:target='agregarAlCarrito({{ $favorito->producto->id }})'>Añadir al carrito</span>
                            <span wire:loading wire:target="agregarAlCarrito({{ $favorito->producto->id }})">Agregando...</span>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center py-4 text-2xl font-semibold text-gray-500">No tienes productos en favoritos.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación con divisoria superior -->
    @if ($favoritos->isNotEmpty())
        <hr class="my-6 border-gray-300"> <!-- Línea divisoria -->

        <div class="mt-4 text-right">
            <nav aria-label="Page navigation">
                <ul class="flex justify-center space-x-2">
                    @if ($favoritos->onFirstPage())
                        <li class="disabled">
                            <span class="px-4 py-2 bg-gray-300 text-gray-500 cursor-not-allowed">Anterior</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $favoritos->previousPageUrl() }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">Anterior</a>
                        </li>
                    @endif

                    @for ($i = 1; $i <= $favoritos->lastPage(); $i++)
                        <li>
                            @if ($i == $favoritos->currentPage())
                                <span class="px-4 py-2 bg-blue-500 text-white rounded">{{ $i }}</span>
                            @else
                                <a href="{{ $favoritos->url($i) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-blue-500 hover:text-white transition duration-300">{{ $i }}</a>
                            @endif
                        </li>
                    @endfor

                    @if ($favoritos->hasMorePages())
                        <li>
                            <a href="{{ $favoritos->nextPageUrl() }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">Siguiente</a>
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
