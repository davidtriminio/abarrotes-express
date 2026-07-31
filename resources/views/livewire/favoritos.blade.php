<div class="container mx-auto py-8">
    <!-- Título centrado -->
    <div class="text-center mb-12 lg:mb-20 mt-16">
        <h2 class="text-5xl font-bold mb-4"><span class="text-primary">Mis </span><span class="text-primary">Favoritos</span></h2>
        <p class="my-7">Explora tu espacio personal, donde los productos que amas y los que aún no conoces te esperan. ¡Descubre, selecciona y hazlos tuyos!</p>
    </div>

    <!-- Contenedor de tabla con productos -->
    <div class="bg-white rounded-lg shadow-lg p-6 overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-4"> <!-- Tabla con espaciado entre filas -->
            <thead>
            <tr class="text-gray-600 bg-gray-100 text-sm uppercase font-medium">
                <th class="py-3 px-6 text-left">Producto</th>
                <th class="py-3 px-6 text-center">Precio</th>
                <th class="py-3 px-6 text-center">Añadir</th>
            </tr>
            </thead>
            <tbody>
            @forelse($favoritos as $favorito)
                <tr class="transition-all duration-300 hover:bg-gray-50 border-b border-gray-200">
                    <!-- Columna de producto con imagen y nombre -->
                    <td class="py-4 px-6 flex items-center space-x-4">
                        <img class="h-16 w-16 rounded-lg object-cover shadow-sm"
                             src="{{ isset($favorito->producto->imagenes[0]) ? url('storage/' . $favorito->producto->imagenes[0]) : asset('imagen/no-photo.png') }}"
                             alt="{{ $favorito->producto->nombre }}">
                        <span class="font-semibold text-gray-800">{{ $favorito->producto->nombre }}</span>
                    </td>

                    <!-- Columna de precio -->
                    <td class="py-4 px-6 text-center">
                        <span class="text-lg font-bold text-gray-800">L. {{ $favorito->producto->precio }}</span>
                    </td>

                    <!-- Columna de botón para agregar al carrito -->
                    <td class="py-4 px-6 text-center">
                        <button wire:click="agregarAlCarrito({{ $favorito->producto->id }})"
                                class="bg-blue-500 text-white py-2 px-4 rounded-lg shadow-sm transition-all duration-200 hover:bg-blue-600">
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
