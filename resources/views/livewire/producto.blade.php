<div>
    <!-- Breadcrumbs -->
    <section id="breadcrumbs" class="pt-6 bg-gray-50">
        <div class="container mx-auto px-4 justify-items-center">
            <ol class="list-reset flex">
                <li><a href="{{route('inicio')}}" class="font-semibold hover:text-primary">Inicio</a></li>
                <li><span class="mx-2">&gt;</span></li>
                <li><a href="{{route('inicio')}}" class="font-semibold hover:text-primary">Productos</a></li>
                <li><span class="mx-2">&gt;</span></li>
                <li><a href="/categorias/{{$producto ->categoria->enlace}}"
                       class="font-semibold hover:text-primary">{{$producto ->categoria->nombre}}</a></li>
                <li><span class="mx-2">&gt;</span></li>
                <li>{{$producto->nombre}}</li>
            </ol>
        </div>
    </section>

    <!-- Product info -->
    <section id="product-info">
        <div class="container mx-auto">
            <div class="py-6">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Image Section -->
                    <div class="w-full lg:w-1/2">
                        <div class="grid gap-4">
                            <!-- Big Image -->
                            <div id="main-image-container">
                                <img id="main-image"
                                     class="h-auto w-full max-w-full rounded-lg object-cover object-center md:h-[480px] transition-transform duration-300 ease-in-out transform hover:scale-105"
                                     src="{{ isset($producto->imagenes[0]) ? url('storage', $producto->imagenes[0]) : asset('imagen/no-photo.png') }}"
                                     alt="{{ $producto->nombre }}"/>
                            </div>
                            <!-- Small Images -->
                            <div class="grid grid-cols-5 gap-4">
                                @if(is_array($producto->imagenes) && count($producto->imagenes) > 0)
                                    @foreach($producto->imagenes as $imagen)
                                        <img onclick="changeImage(this)"
                                             data-full="{{ isset($imagen) ? url('storage' . $imagen) : asset('imagen/no-photo.png') }}"
                                             src="{{ isset($imagen) ? url('storage' . $imagen) : asset('imagen/no-photo.png') }}"
                                             class="object-cover object-center max-h-30 max-w-full rounded-lg cursor-pointer border-2 border-transparent hover:border-primary transition-shadow duration-300 ease-in-out shadow-sm hover:shadow-lg"
                                             alt="{{ $producto->nombre }}"/>
                                    @endforeach
                                @else
                                    <p class="grid-cols-5 gap-4">No hay imagenes disponibles para este producto.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Product Details Section -->
                    <div class="w-full lg:w-1/2 flex flex-col justify-between">
                        <div class="pb-8 border-b border-gray-line">
                            <h1 class="text-5xl font-bold mb-4">{{$producto ->nombre}}</h1>
                            <div class="mb-4 pb-4 border-b border-gray-line place-self-end">
                                <p class="mb-2 text-3xl">Marca: <strong><a href="#"
                                                                           class="hover:text-primary">{{$producto ->marca->nombre}}</a></strong>
                                </p>
                                <p class="mb-2 text-3xl">Categoría: <strong><a href="#" class="hover:text-primary">{{$producto ->categoria -> nombre}}</a></strong>
                                </p>
                                <p class="mb-2 text-2xl">Código de Producto: <strong>00000</strong></p>
                                <p class="mb-2 text-2xl">Disponible:
                                    @if($producto->disponible)
                                        <strong>Sí</strong>
                                    @else
                                        <strong>No</strong>
                                    @endif
                                </p>
                            </div>
                            <div class="font-semibold mb-8 text-4xl"><span
                                    class="font-bold text-primary">L. @if($producto->en_oferta)
                                        {{ $producto->precio - ($producto->precio * ($producto->porcentaje_oferta / 100)) }}
                                    @else
                                        {{ $producto->precio }}
                                    @endif</span>
                                @if($producto->en_oferta)
                                    <span class="line-through ml-2 text-gray-200">L. {{$producto->precio}}</span>
                                @endif</div>
                            <div class="flex items-center mb-8">
                                <button wire:click="decrementarCantidad" id="decrease"
                                        class="bg-primary hover:bg-transparent border border-transparent hover:border-primary text-white hover:text-primary font-semibold w-10 h-10 rounded-full flex items-center justify-center focus:outline-none">
                                    -
                                </button>
                                <input id="quantity" type="number" wire:model="cantidad"
                                       class="w-16 py-2 text-center focus:outline-none text-2xl" readonly>
                                <button wire:click="incrementarCantidad" id="increase"
                                        class="bg-primary hover:bg-transparent border border-transparent hover:border-primary text-white hover:text-primary font-semibold w-10 h-10 rounded-full focus:outline-none">
                                    +
                                </button>

                                <!-- Espacio entre el contador y el corazón -->
                                <div class="ml-4">
                                    <!-- Solo mostrar si el usuario está autenticado -->
                                    @auth
                                        <!-- Agregar/Eliminar de Favoritos -->
                                        @if($esFavorito)
                                            <!-- Corazón lleno para favorito -->
                                            <button wire:click="eliminarFavorito" class="text-red-500 hover:text-red-700 focus:outline-none">
                                                <span class="icon-[fxemoji--redheart] text-3xl"></span>
                                            </button>
                                        @else
                                            <!-- Corazón sin relleno para cuando no es favorito -->
                                            <button wire:click="agregarFavorito" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                                <span class="icon-[mdi--heart-outline] text-3xl"></span>
                                            </button>
                                        @endif
                                    @endauth

                                    <!-- Mensaje para usuarios no autenticados -->
                                    @guest
                                        <p class="text-lg text-gray-700 font-semibold mt-2">Inicia sesión para agregar este producto a tus favoritos.</p>
                                    @endguest
                                </div>



                            </div>

                            <!-- Agregar al Carrito -->
                            <button wire:click="agregarAlCarrito({{$producto->id}})"
                                    class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-6 rounded-full text-xl">
                                <span wire:loading.remove wire:click='agregarAlCarrito({{ $producto -> id }})'>Añadir al carrito</span>
                                <span wire:loading
                                      wire:target="agregarAlCarrito({{$producto -> id}})">Agregando...</span></button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function changeImage(element) {
        const mainImage = document.getElementById('main-image');
        mainImage.src = element.getAttribute('data-full');
    }
</script>
