<div class="container mx-auto max-w-screen-xl px-4 categories">
    <div class="text-center title-container mb-12 lg:mb-20">
        <h2 class="text-5xl font-weight-medium mb-4">Explora <span class="text-primary">Nuestras Marcas</span></h2>
        <p class="my-7 text-muted">Explora nuestra selección de marcas destacadas y descubre productos especialmente seleccionados para ti. ¡Encuentra calidad y variedad en cada marca que ofrecemos!</p>
    </div>

    <!-- Barra de Búsqueda -->


    <!-- Contenedor de Tarjetas -->
    <div class="cards-container flex flex-wrap">
        @forelse ($marcas as $marca)
            <div class="my-2 mx-auto p-relative bg-white shadow-1 blue-hover" style="width: 360px; overflow: hidden; border-radius: 1px;" data-name="{{ strtolower($marca->nombre) }}">
                <div class="card-bg">
                    <img src="{{ isset($marca->imagen) ? url(asset('storage/' . $marca->imagen)) : asset('imagen/no-photo.png') }}" alt="{{ $marca->nombre }}" class="image-full">
                </div>
                <div class="px-2 py-2">
                    <p class="mb-0 small font-weight-medium text-uppercase mb-1 text-muted lts-2px">
                        {{ $marca->nombre }}
                    </p>

                    <h1 class="font-weight-normal text-black card-heading mt-0 mb-1" style="line-height: 1.25;">
                        {{ $marca->nombre }}
                    </h1>

                    <p class="card-description mb-1">
                        {{ $marca->descripcion }}
                    </p>
                </div>

                <a href="{{ route('productos', [0,'marca' => $marca->id]) }}" class="text-uppercase d-inline-block font-weight-medium lts-2px ml-2 mb-2 text-center styled-link">
                    Ver más
                </a>
            </div>
        @empty
            <div class="text-center">
                <p class="text-muted">No hay marcas disponibles en este momento. ¡Vuelve pronto para descubrir más!</p>
            </div>
        @endforelse
    </div>

    @if ($marcas->isNotEmpty())
        <div class="text-center mt-4">
            {{ $marcas->links() }}
        </div>
    @endif
</div>
