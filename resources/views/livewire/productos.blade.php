<div>
    <!-- Shop -->
    <section id="shop">
        <div class="container mx-auto">
            <!-- Top Filter -->
            <div class="flex flex-col md:flex-row justify-between items-center py-4">
                <div class="flex items-center space-x-4">

                </div>
                <div class="flex space-x-4 z-0">
                    <div class="relative fixed">
                        <select
                            class="block appearance-none w-full bg-white border hover:border-primary px-4 py-2 pr-8 rounded-full shadow leading-tight focus:outline-none focus:shadow-outline"
                            wire:model="orden" wire:click="precios">
                            <option value="tiempo" selected>Producto Reciente</option>
                            <option value="caro">Precio más Alto</option>
                            <option value="barato">Precio más Bajo</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center justify-center px-2">
                            <img id="arrow-down" class="h-4 w-4" src="/imagen/filter.svg" alt="filter arrow">
                            <img id="arrow-up" class="h-4 w-4 hidden" src="/imagen/filter-up-arrow.svg"
                                 alt="filter arrow">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filter Toggle Button for Mobile -->
            <div class="block md:hidden text-center mb-4">
                <button id="products-toggle-filters"
                        class="bg-primary text-white py-2 px-4 rounded-full focus:outline-none">Filtrar Productos
                </button>
            </div>
            <div class="flex flex-col md:flex-row">
                <!-- Filtro -->
                <div id="filters" class="w-full md:w-1/4 p-4 hidden md:block">
                    <!-- Catgoria -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Categorías</h3>
                        <div class="space-y-2">

                            @forelse($categorias->take($mostrarTodasCategorias ? $categorias->count() : $categoriasVisibles) as $categoria)
                                @if($categoria->disponible == true)
                                    <label class="flex items-center">
                                        <input type="checkbox" class="form-checkbox custom-checkbox"
                                               value="{{ $categoria->id }} " wire:model="categoriasFiltradas"
                                               wire:click="filtromarcas">
                                        <span class="ml-2">{{$categoria->nombre}}</span>
                                    </label>
                                @endif
                            @empty
                                <li>No se encontraron categorías.</li>
                                </ul>

                            @endforelse
                        </div>
                        @if($categorias->count() > $categoriasVisibles && $categorias->count() > 5)
                            <button wire:click.prevent="toggleCategorias" class="mt-4 text-primary">
                                {{ $mostrarTodasCategorias ? 'Ver menos' : 'Ver más' }}
                            </button>
                        @endif
                    </div>
                    <!-- Marcas -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Marcas</h3>
                        <div class="space-y-2">
                            @forelse($marcas->take($mostrarTodasMarcas ? $marcas->count() : $marcasVisibles) as $marca)
                                @if($marca->disponible == true)
                                    <label class="flex items-center">
                                        <input type="checkbox" class="form-checkbox custom-checkbox"
                                               value="{{ $marca->id }} " wire:model="marcasFiltradas"
                                               wire:click="filtromarcas">
                                        <span class="ml-2">{{$marca->nombre}}</span>
                                    </label>
                                @endif
                            @empty
                                <li>No se encontraron marcas.</li>
                                </ul>
                            @endforelse
                        </div>
                        @if($marcas->count() > $marcasVisibles && $marcas->count() > 5)
                            <button wire:click.prevent="toggleMarcas" class="mt-4 text-primary">
                                {{ $mostrarTodasMarcas ? 'Ver menos' : 'Ver más' }}
                            </button>
                        @endif
                    </div>
                    <div>
                        <label for="precio">Rango de Precio: </label>
                        <input type="range" id="precio" min="{{$precioMinimo}}" max="{{ $precioMaximo }}" step="1"
                               wire:model.live="precio"/><br>
                        <span>L. {{ $precioMinimo }} -  L. {{ $precio }}</span>
                    </div>

                </div>
                <!-- Products List -->
                <div class="flex flex-wrap -mx-3 w-6/10 dim">
                    @forelse ($productos ?? [] as $producto)
                        @if($producto->disponible)
                            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/4 px-4 mb-8">
                                <div class="bg-white p-3 rounded-lg shadow-lg text-center hover:bg-gray-100">
                                    <!-- Hacer toda la tarjeta clicable -->
                                    <a href="{{ route('producto', ['id' => $producto->id]) }}" class="block">

                                        <img src="{{ isset($producto->imagenes[0]) ? url('storage' , $producto->imagenes[0]) : asset('imagen/no-photo.jpg') }}"
                                             class="w-full object-cover mb-4 rounded-lg tamanoCard" alt="{{$producto->nombre}}">
                                        <h3 class="text-lg font-semibold mb-2 text-primary">{{$producto->nombre}}</h3>
                                    </a>
                                    <div class="flex items-center justify-center mb-4">
                                        <span
                                            class="text-lg font-bold text-primary">L. {{ $producto->precio - ($producto->precio * ($producto->porcentaje_oferta / 100)) }}</span>
                                        @if($producto->en_oferta)
                                            <span class="text-sm line-through ml-2">L. {{$producto->precio}}</span>
                                        @endif
                                    </div>

                                    <!-- Botón de Añadir al Carrito -->
                                    <button wire:click="agregarAlCarrito({{$producto->id}})"
                                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full">
                                        <span wire:loading.remove wire:target="agregarAlCarrito({{$producto->id}})">Añadir al carrito</span>
                                        <span wire:loading wire:target="agregarAlCarrito({{$producto->id}})">Agregando...</span>
                                    </button>
                                    <div class="flex justify-end items-center mt-3 text-ddd">
                                        <a href="{{ route('producto', ['id' => $producto->id]) }}"
                                           class="text-ddd flex items-center" style="color: #BBB;">
                                            <span class="mr-2">Ver producto</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" style="color: #BBB;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                      d="M12 4.5c-7.5 0-12 7.5-12 7.5s4.5 7.5 12 7.5 12-7.5 12-7.5-4.5-7.5-12-7.5zm0 3a4.5 4.5 0 110 9 4.5 4.5 0 010-9z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <p>No se encontraron productos.</p>
                    @endforelse
                </div>
            </div>
        </div>
        @if ($productos->isNotEmpty())
            <div class="flex justify-center mt-4 space-x-4">
                {{ $productos->links() }}
            </div>
        @endif
    </section>

    <!-- Shop category description -->
    <section id="shop-category-description" class="py-8">
        <div class="container mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-4 text-center">Nuestros Productos</h2>
                <p class="mb-4">
                    Descubre nuestra amplia variedad de productos en Abarrote Express,
                    tu tienda de alimentos de confianza. Tenemos todo lo que necesitas para cualquier ocasión, ya sea
                    que estés buscando algo para una comida casual o algo más formal. Nuestra colección incluye una
                    amplia gama de productos de alta calidad, desde enlatados y productos de despensa hasta lácteos,
                    frutas y verduras frescas. También ofrecemos una selección de bebidas no alcohólicas, alimentos
                    preparados, carnes y embutidos,
                    productos de higiene personal, artículos para uso doméstico y productos de limpieza.
                </p>
                <p>
                    En Abarrote Express nos enorgullece ofrecer una amplia variedad de marcas y productos para
                    satisfacer todos los gustos y necesidades. Nuestro objetivo es brindarte una experiencia de compra
                    conveniente y satisfactoria,
                    con precios competitivos y un servicio amigable.
                </p>
            </div>
        </div>
    </section>
</div>