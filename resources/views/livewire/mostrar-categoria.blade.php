<div class="container mx-auto max-w-screen-xl px-4 categories">
    <div class="text-center mb-12 lg:mb-20 mt-16">
        <h2 class="text-5xl font-bold mb-4"><span class="text-primary">Explora </span><span class="text-primary">Nuestras Categorías</span></h2>
        <p class="my-7">Sumérgete en nuestra variada colección de categorías y descubre productos seleccionados especialmente para ti. ¡Encuentra lo que necesitas y mucho más!</p>
    </div>

    <div class="flex flex-wrap -mx-4 bg-gray-50 p-4">
        @forelse ($categorias as $categoria)
            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/4 px-4 mb-8">
                <!-- Flex con justify-between para alinear contenido y botón -->
                <div class="bg-white p-3 rounded-lg shadow-lg text-center hover:bg-gray-50 flex flex-col h-full justify-between">
                    <a href="{{ route('productos', ['categoria' => $categoria->id]) }}" class="block">
                        <img src="{{ isset($categoria->imagen) ? url('storage/' . $categoria->imagen) : asset('imagen/no-photo.png') }}"
                             class="w-full h-auto mx-auto mb-4 rounded-lg tamanoCard"
                             alt="{{ $categoria->nombre }}">
                        <h3 class="text-lg font-semibold mb-2 text-primary truncate-title">{{ $categoria->nombre }}</h3>
                    </a>

                    <!-- Limitar altura de la descripción -->
                    <div class="mb-4 overflow-hidden" style="max-height: 4em;">
                        <p class="text-sm text-muted truncate-description">{{ $categoria->descripcion }}</p>
                    </div>

                    <!-- Botón Ver Más alineado al final -->
                    <div class="mt-auto px-4 py-4">
                        <a href="{{ route('productos', ['categoria' => $categoria->id]) }}"
                           class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full">
                            Ver más
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center">
                <p class="text-muted">No hay categorías disponibles en este momento. ¡Vuelve pronto para descubrir más!</p>
            </div>
        @endforelse
    </div>

    <!-- Paginación con divisoria superior para Categorías -->
    @if ($categorias->isNotEmpty())
        <hr class="my-6 border-gray-300"> <!-- Línea divisoria -->

        <div class="mt-4 text-right">
            <nav aria-label="Page navigation">
                <ul class="flex justify-center space-x-2">
                    @if ($categorias->onFirstPage())
                        <li class="disabled">
                            <span class="px-4 py-2 bg-gray-300 text-gray-500 cursor-not-allowed">Anterior</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $categorias->previousPageUrl() }}"
                               class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">Anterior</a>
                        </li>
                    @endif

                    @for ($i = 1; $i <= $categorias->lastPage(); $i++)
                        <li>
                            @if ($i == $categorias->currentPage())
                                <span class="px-4 py-2 bg-blue-500 text-white rounded">{{ $i }}</span>
                            @else
                                <a href="{{ $categorias->url($i) }}"
                                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-blue-500 hover:text-white transition duration-300">{{ $i }}</a>
                            @endif
                        </li>
                    @endfor

                    @if ($categorias->hasMorePages())
                        <li>
                            <a href="{{ $categorias->nextPageUrl() }}"
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
