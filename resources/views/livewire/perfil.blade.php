<div class="perfil-contenedor-nuevo">
    <!-- Cabecera principal con fondo azul marino -->
    <div class="perfil-cabecera">
        <div class="perfil-info">
            <div class="avatar">
                <span class="inicial-avatar">{{ strtoupper(substr($nombreUsuario, 0, 1)) }}</span>
            </div>
            <div class="texto-info">
                <h1 class="nombre-usuario">{{ $nombreUsuario }}</h1>
            </div>
        </div>
        <div class="botones-header">
            <!-- Dropdown para el ícono de la campana -->
            <div class="hs-dropdown [--strategy:static] md:[--strategy:fixed] [--adaptive:none] md:[--trigger:click] md:py-4">
                <button id="hs-dropdown-notifications" type="button"
                        class="hs-dropdown-toggle py-0.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium text-white hover:text-gray-400 focus:outline-none disabled:opacity-50 disabled:pointer-events-none transition ease-in-out">
                    <!-- Ícono de la campana, más grande y negro -->
                    <span class="icon-[majesticons--bell] icon-large  text-2xl"></span>
                    @if($notificaciones->count() > 0)
                        <span class="bg-red-500 text-white rounded-full text-xs px-2 py-1 absolute top-0 right-0">
                {{ $notificaciones->count() }}
            </span>
                    @endif
                    <svg class="hs-dropdown-open:rotate-180 size-4"
                         xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </button>

                <div class="hs-dropdown-menu md:w-48 hidden z-10 min-w-60 bg-white shadow-md rounded-lg p-1 space-y-0.5 mt-2 divide-y divide-gray-200">
                    <div class="py-2 first:pt-0 last:pb-0">
                        <!-- Mostrar las notificaciones aquí -->
                        @forelse($notificaciones as $notificacion)
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                                <span class="icon-[material-symbols--notification-add] text-black text-3xl"></span>
                                <div class="text-sm text-black text-justify">
                                    <p>{{ $notificacion['mensaje'] }}</p>
                                    <span class="text-xs text-gray-500">{{ $notificacion['fecha'] }}</span>
                                </div>
                            </a>
                        @empty
                            <p class="py-2 px-3 text-sm text-gray-500">No hay nuevas notificaciones.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <!-- Fin del dropdown -->



        </div>
    </div>


    <!-- Menú de navegación estilo tarjetas -->
    <div class="perfil-menu-nuevo">
        <div class="menu-tarjetas">
            <a class="menu-item" id="miCuenta">
                <span class="icon-[ph--user]"></span>
                <p>Mi Cuenta</p>
            </a>
            <a class="menu-item" id="misOrdenes">
                <span class="icon-[bi--truck]"></span>
                <p>Mis Ordenes</p>
            </a>
        </div>
    </div>


    <div class="tarjetas-resumen" id="tarjetasResumen"></div>
</div>

<script>
    // Selección de elementos del DOM
    const tarjetasResumen = document.getElementById('tarjetasResumen');
    const menuItems = {
        miCuenta: document.getElementById('miCuenta'),
        misOrdenes: document.getElementById('misOrdenes')
    };


    const contenidoTarjetas = {
        miCuenta: `
            <a href="{{ route('editarperfil') }}" class="tarjeta">
                <div class="texto-tarjeta" style="text-align: center;">
                    <span class="icon-[material-symbols--edit-square-outline-sharp]"></span>
                    <p>Editar mis datos</p>
                </div>
            </a>
            <a href="#" class="tarjeta">
                <div class="texto-tarjeta" style="text-align: center;">
                    <span class="icon-[streamline--payment-10]"></span>
                    <p>Métodos de pago</p>
                </div>
            </a>
            <a  class="tarjeta" onclick="abrirVentanaYDescargar()">
                <div class="texto-tarjeta" style="text-align: center;">
                    <span class="icon-[charm--padlock]"></span>
                    <p>Clave de Recuperación</p>
                </div>
            </a>
            <a href="{{ route('favoritos') }}" class="tarjeta">
                <div class="texto-tarjeta" style="text-align: center;">
                    <span class="icon-[bi--heart]"></span>
                    <p>Mis favoritos</p>
                </div>
            </a>
        `,
        misOrdenes: `
         <a  class="tarjeta" wire:click="redirigirOrdenes('nuevo')">
            <div class="texto-tarjeta" style="text-align: center;">
                <span class="icon-[hugeicons--package-moving]"></span>
                <p>Nuevo</p>
                <h3>{{ $contadorNuevo }}</h3>
            </div>
        </a>
        <a  class="tarjeta" wire:click="redirigirOrdenes('procesado')">
            <div class="texto-tarjeta" style="text-align: center;">
                <span class="icon-[hugeicons--package-process]"></span>
                <p>En proceso</p>
                <h3>{{ $contadorProceso }}</h3>
            </div>
        </a>
        <a  class="tarjeta" wire:click="redirigirOrdenes('enviado')">
            <div class="texto-tarjeta" style="text-align: center;">
                <span class="icon-[hugeicons--package-open]"></span>
                <p>Enviado</p>
                <h3>{{ $contadorEnviado }}</h3>
            </div>
        </a>
        <a  class="tarjeta" wire:click="redirigirOrdenes('entregado')">
            <div class="texto-tarjeta" style="text-align: center;">
                <span class="icon-[hugeicons--package-delivered]"></span>
                <p>Entregado</p>
                <h3>{{ $contadorEntregado }}</h3>
            </div>
        </a>

        `
    };


    function mostrarContenido(tipo) {
        tarjetasResumen.innerHTML = contenidoTarjetas[tipo];
        tarjetasResumen.style.display = 'flex';
    }


    document.addEventListener('DOMContentLoaded', function () {
        mostrarContenido('miCuenta');
    });


    menuItems.miCuenta.addEventListener('click', function (event) {
        event.preventDefault();
        mostrarContenido('miCuenta');
    });

    menuItems.misOrdenes.addEventListener('click', function (event) {
        event.preventDefault();
        mostrarContenido('misOrdenes');
    });
</script>

<script>
    function abrirVentanaYDescargar() {

        const ventanaVacia = window.open('about:blank', '_blank', 'width=400,height=300');


        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = '/exportarClaveRecuperacion';
        document.body.appendChild(iframe);


        setTimeout(() => {
            ventanaVacia.close();
        }, 4000);
    }
</script>





