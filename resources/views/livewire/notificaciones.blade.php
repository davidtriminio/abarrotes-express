<div class="container mx-auto px-4">
    <!-- Título centrado -->
    <div class="text-center mb-12 lg:mb-20 mt-16">
        <h2 class="text-5xl font-bold mb-4">
            <span class="text-primary">Mis </span>
            <span class="text-primary">Notificaciones</span>
        </h2>
        <p class="my-7">Aquí puedes ver todas las actualizaciones sobre tus órdenes. Haz clic para más detalles.</p>
    </div>

    <!-- Lista de notificaciones -->
    <div class="bg-white shadow-lg rounded-lg p-6">
        @if(count($notificaciones) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($notificaciones as $notificacion)
                    <li class="py-4 flex justify-between items-center">
                        <div>
                            <p class="text-gray-800 text-lg font-semibold">
                                {{ $notificacion['mensaje'] }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $notificacion['fecha'] }}
                            </p>
                        </div>
                        <a href="{{ $notificacion['ruta'] }}"
                           class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                            Ver detalles
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 text-center">No tienes notificaciones en este momento.</p>
        @endif
    </div>

    <!-- Paginación con divisoria superior -->
    @if ($notificaciones->isNotEmpty())
        <div class="mt-4 text-right">
            <nav aria-label="Page navigation">
                <ul class="flex justify-center space-x-2">
                    @if ($notificaciones->onFirstPage())
                        <li class="disabled">
                            <span class="px-4 py-2 bg-gray-300 text-gray-500 cursor-not-allowed">Anterior</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $notificaciones->previousPageUrl() }}"
                               class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">Anterior</a>
                        </li>
                    @endif

                    @for ($i = 1; $i <= $notificaciones->lastPage(); $i++)
                        <li>
                            @if ($i == $notificaciones->currentPage())
                                <span class="px-4 py-2 bg-blue-500 text-white rounded">{{ $i }}</span>
                            @else
                                <a href="{{ $notificaciones->url($i) }}"
                                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-blue-500 hover:text-white transition duration-300">{{ $i }}</a>
                            @endif
                        </li>
                    @endfor

                    @if ($notificaciones->hasMorePages())
                        <li>
                            <a href="{{ $notificaciones->nextPageUrl() }}"
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
