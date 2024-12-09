<div>
    <section id="product-slider">
        <div class="main-slider swiper-container">
            <div class="swiper-wrapper">
                <section id="brands" class="bg-white py-16 px-4">
                    <div class="container mx-auto max-w-screen-xl px-4 testimonials">
                        <div class="text-center mb-12 lg:mb-20">
                            <h2 class="text-5xl font-bold mb-4"><span class="text-primary">Nuestros Productos</span>
                            </h2>
                            </h2>
                            <p class="my-7">¡Descubre nuestros increíbles productos! Sumérgete en una experiencia única
                                y encuentra todo lo que necesitas en un solo lugar.</p>

                        </div>

                        <!-- Traer los productos de la base de datos -->
                        <div
                            class="flex flex-wrap -mx-4 bg-gray-50 p-4  @if($productos->where('disponible', true)->count() < 4) justify-center @endif "
                        >
                            @forelse ($productos as $producto)
                                @if($producto->disponible)
                                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/4 px-4 mb-8">
                                        <div class="bg-white p-3 rounded-lg shadow-lg text-center hover:bg-gray-50">
                                            <!-- Hacer toda la tarjeta clicable -->
                                            <a href="{{ route('producto', ['id' => $producto->id]) }}" class="block">

                                                <img src="{{ isset($producto->imagenes[0]) ? url('storage/' . $producto->imagenes[0]) : asset('imagen/no-photo.png') }}"
                                                     class="w-full h-auto mx-auto mb-4 rounded-lg tamanoCard"
                                                     alt="{{ $producto->imagenes[0] ?? 'Imagen no disponible' }}">
                                                <h3 class="text-lg font-semibold mb-2 text-primary">{{$producto->nombre}}</h3>
                                            </a>
                                            <div class="flex items-center justify-center mb-4">
                                                <span class="text-lg font-bold text-primary">L. {{ $producto->precio - ($producto->precio * ($producto->porcentaje_oferta / 100)) }}</span>
                                                @if($producto->porcentaje_oferta > 0)
                                                    <span class="text-sm line-through ml-2">L. {{$producto->precio}}</span>
                                                @endif
                                            </div>

                                            <!-- Botón de Añadir al Carrito -->
                                            <button wire:click="agregarCarrito({{$producto->id}})"
                                                    class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full">
                                                <span wire:loading.remove
                                                      wire:target="agregarCarrito({{$producto->id}})">Añadir al carrito</span>
                                                <span wire:loading wire:target="agregarCarrito({{$producto->id}})">Agregando...</span>
                                            </button>
                                            <div class="flex justify-end items-center mt-3 text-ddd">
                                                <a href="{{ route('producto', ['id' => $producto->id]) }}"
                                                   class="text-ddd flex items-center" style="color: #BBB;">
                                                    <span class="mr-2">Ver producto</span>
                                                    <span class="icon-[lucide--eye]"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <p>No se encontraron productos.</p>
                            @endforelse
                        </div>


                        <section id="brands" class="bg-white py-16 px-4">
                            <div class="container mx-auto max-w-screen-xl px-4 testimonials">
                                <div class="text-center mb-12 lg:mb-20">
                                    <h2 class="text-5xl font-bold mb-4">Descubra <span class="text-primary">Nuestras Categorías</span>
                                    </h2>
                                    <p class="my-7">Descubre las principales categorías que ofrecemos en nuestra tienda
                                        y
                                        explora todo lo que tenemos para ti.</p>
                                </div>

                                <!-- Traer los categoria de la base de datos -->
                                <!-- Traer las categorías de la base de datos -->
                                <div
                                    class="flex flex-wrap -mx-4 bg-gray-50 p-4  @if($categorias->where('disponible', true)->count() < 2) justify-center @endif">
                                    @forelse ($categorias as $categoria)
                                        @if($categoria->disponible == true)
                                            <div class="w-full sm:w-1/2 md:w-1/4 lg:w-1/4 xl:w-1/4 px-4 mb-8">
                                                <a href="{{ route('productos', ['categoria' => $categoria->id]) }}"
                                                   class="block bg-white p-3 rounded-lg shadow-lg hover:bg-gray-50 transition">
                                                    <img src="{{ isset($categoria->imagen) ? url('storage/' . $categoria->imagen) : asset('imagen/no-photo.png') }}"
                                                         class="w-full object-cover mb-4 rounded-lg tamanoCard h-auto mx-auto"
                                                         alt="{{ $categoria->nombre }}">
                                                    <h3 class="text-lg font-semibold mb-2 text-center hover:text-primary">{{ $categoria->nombre }}</h3>
                                                </a>
                                            </div>
                                        @endif
                                    @empty
                                        <p>No se encontraron categorías.</p>
                                    @endforelse
                                </div>
                            </div>
                        </section>
                        <!-- Cambio en marcas -->
                        <section id="brands" class="bg-white py-16 px-4">
                            <div class="container mx-auto max-w-screen-xl px-4">
                                <div class="text-center mb-12 lg:mb-20">
                                    <h2 class="text-5xl font-bold mb-4">Descubra <span class="text-primary">Nuestras Marcas</span>
                                    </h2>
                                    <p class="my-7">Explora las principales marcas que presentamos en nuestra tienda</p>
                                </div>
                                <div
                                    class="flex flex-wrap -mx-4 bg-gray-50 p-4 @if($marcas->where('disponible', true)->count() < 4) justify-center @endif">
                                    @forelse ($marcas as $marca)
                                        @if($marca->disponible == true)
                                            <div class="w-full sm:w-1/2 md:w-1/4 lg:w-1/4 xl:w-1/3 px-4 mb-8">
                                                <a href="{{ route('productos', [0, 'marca' => $marca->id]) }}"
                                                   class="block bg-white p-3 rounded-lg shadow-lg hover:bg-gray-50 transition">
                                                    <img src="{{ isset($marca->imagen) ? url('storage/' . $marca->imagen) : asset('imagen/no-photo.png') }}"
                                                         class="w-full object-cover mb-4 rounded-lg tamanoCard h-auto mx-auto"
                                                         alt="{{ $marca->nombre }}">
                                                    <h3 class="text-lg font-semibold mb-2 text-center hover:text-primary">{{ $marca->nombre }}</h3>
                                                </a>
                                            </div>
                                        @endif
                                    @empty
                                        <p>No se encontraron las marcas.</p>
                                    @endforelse
                                </div>
                            </div>
                        </section>

                        <section class="py-16">
                            <div class="relative items-center w-full px-5 py-12 mx-auto md:px-12 lg:px-24 max-w-7xl">
                                <div class="grid w-full grid-cols-1 gap-6 mx-auto lg:grid-cols-3">
                                    <div class="flex flex-col p-6 bg-white rounded-xl shadow-lg">
                                        <h1 class="mb-4 text-2xl font-semibold leading-none tracking-tighter text-gray-dark lg:text-3xl text-center">
                                            Compra 100% segura</h1>
                                        <p class="flex-grow text-base font-medium leading-relaxed text-gray-txt">Compra
                                            con
                                            confianza en abarrotes-express, tu tienda en línea donde garantizamos una
                                            experiencia satifatoria</p>
                                        <img class="image-svg h-auto m-auto object-cover mb-4 rounded-lg tamanoCard2 h-auto mx-auto"
                                             src="/imagen/segurida.svg" alt="blog">

                                    </div>
                                    <div class="flex flex-col p-6 bg-white rounded-xl shadow-lg">
                                        <h1 class="mb-4 text-2xl font-semibold leading-none tracking-tighter text-gray-dark lg:text-3xl text-center">
                                            Metodo de pago</h1>
                                        <p class="flex-grow text-base font-medium leading-relaxed text-gray-txt">
                                            Aceptamos
                                            pagos únicamente con tarjeta de crédito o débito! Puedes realizar tus
                                            compras de
                                            manera segura y conveniente utilizando cualquiera de estas dos opciones de
                                            pago.</p>
                                        <img class="image-svg h-auto m-auto object-cover mb-4 rounded-lg tamanoCard2"
                                             src="imagen/tarjeta.svg" alt="blog">
                                    </div>
                                    <div class="flex flex-col p-6 bg-white rounded-xl shadow-lg">
                                        <h1 class="mb-4 text-2xl font-semibold leading-none tracking-tighter text-gray-dark lg:text-3xl text-center">
                                            Recibe tu producto</h1>
                                        <p class="flex-grow text-base font-medium leading-relaxed text-gray-txt">
                                            Coordina la
                                            entrega de tu compra directamente con el vendedor. Tienes la opción de
                                            recibirlo
                                            cómodamente en tu domicilio, en la oficina o elegir recogerlo personalmente.
                                            ¡Tú
                                            tienes la libertad de decidir lo que más te convenga!.</p>
                                        <img class="image-svg h-auto m-auto object-cover mb-4 rounded-lg tamanoCard2"
                                             src="imagen/envio.svg"
                                             alt="blog">

                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
