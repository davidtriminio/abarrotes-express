<div class="container mx-auto max-w-screen-xl px-4 categories">
    <div class="text-center title-container mb-12 lg:mb-20">
        <h2 class="text-5xl font-weight-medium mb-4">Explora <span class="text-primary">Nuestras Categorías</span></h2>
        <p class="my-7 text-sky-950">Sumérgete en nuestra variada colección de categorías y descubre productos seleccionados especialmente para ti. ¡Encuentra lo que necesitas y mucho más!</p>
    </div>

    <div class="cards-container flex flex-wrap">
        @forelse ($categorias as $categoria)
            <div class="my-2 mx-auto p-relative bg-white shadow-1 blue-hover" style="width: 360px; overflow: hidden; border-radius: 1px;">
                <div class="card-bg">
                    <img src="{{ isset($categoria->imagen) ? url(asset('storage/' . $categoria->imagen)) : asset('imagen/no-photo.png') }}" alt="{{ $categoria->nombre }}" class="image-full">
                </div>


                <div class="px-4 py-2 flex-grow">
                    <p class="mb-0 small font-weight-medium text-uppercase mb-1 text-muted lts-2px">
                        {{ $categoria->nombre }}
                    </p>

                    <h1 class="font-weight-normal text-black card-heading mt-0 mb-1" style="line-height: 1.25;">
                        {{ $categoria->nombre }}
                    </h1>

                    <p class="card-description mb-1">
                        {{ $categoria->descripcion }}
                    </p>
                </div>

                <div class="px-4 py-4">
                    <a href="{{ route('productos', ['categoria' => $categoria->id]) }}" class="text-uppercase d-inline-block font-weight-medium lts-2px text-center styled-link">
                        Ver más
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center">
                <p class="text-muted">No hay categorías disponibles en este momento. ¡Vuelve pronto para descubrir más!</p>
            </div>
        @endforelse
    </div>

    @if ($categorias->isNotEmpty())
        <div class="text-center mt-4">
            {{ $categorias->links() }}
        </div>
    @endif
</div>
