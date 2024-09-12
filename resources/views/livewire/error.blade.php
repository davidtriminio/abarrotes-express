<x-layouts.app>
    <main>
        <section id="pagina-404" class="fondo-blanco py-16 flex items-center justify-center min-h-screen">
            <div class="mx-auto max-w-screen-lg px-4 md:px-8">
                <div class="grid gap-8 sm:grid-cols-2">
                    <div class="flex flex-col items-center justify-center sm:items-start md:py-24 lg:py-32">
                        <h1 class="texto-4xl fuente-negrita color-principal mb-5">{{$titulo}}</h1>
                        <p class="texto-gris mb-5">{{$mensaje}}</p>
                        <div class="botones-contenedor">
                            <a href="{{ route('inicio') }}" class="boton-regresar">Ir al Inicio</a>
                        </div>
                    </div>
                    <div class="relativo h-80 overflow-hidden md:h-auto">
                        <img src="{{url(asset('imagen/error_ilustracion.svg'))}}" alt="Imagen de Error" class="w-full h-auto">
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
