<div class="container mx-auto py-8">
    <!-- Título centrado -->
    <h1 class="text-4xl font-bold text-center mb-6 text-primary">MIS FAVORITOS</h1>

    <div class="flex flex-wrap justify-center -mx-3">
        @forelse($favoritos as $favorito)
            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/4 px-4 mb-8">
                <div class="bg-white p-4 rounded-lg shadow-lg text-center hover:bg-gray-100 relative">
                    <a href="{{ route('producto', ['id' => $favorito->producto->id]) }}" class="block">
                        <img src="{{ isset($favorito->producto->imagenes[0]) ? url('storage', $favorito->producto->imagenes[0]) : asset('imagen/no-photo.jpg') }}"
                             class="w-full object-cover mb-4 rounded-lg h-48" alt="{{ $favorito->producto->nombre }}">
                        <h3 class="text-lg font-semibold mb-2 text-primary">{{ $favorito->producto->nombre }}</h3>
                    </a>
                    <div class="flex items-center justify-center mb-4">
                        <span class="text-lg font-bold text-primary">L. {{ $favorito->producto->precio }}</span>
                    </div>

                    <!-- Botón para agregar al carrito -->
                    <button wire:click="agregarAlCarrito({{ $favorito->producto->id }})"
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-6 rounded-full text-xl">
                        <span wire:loading.remove wire:target='agregarAlCarrito({{ $favorito->producto->id }})'>Añadir al carrito</span>
                        <span wire:loading wire:target="agregarAlCarrito({{ $favorito->producto->id }})">Agregando...</span>
                    </button>

                </div>
            </div>
        @empty
            <p class="text-center text-lg">No tienes productos en favoritos.</p>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $favoritos->links() }}
    </div>

    </div>
</div>
