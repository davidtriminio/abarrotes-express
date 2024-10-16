<div class="perfil-contenedor-nuevo">
    <!-- Cabecera principal con fondo azul marino -->
    <div class="perfil-cabecera">
        <div class="perfil-info">
            <div class="avatar">
                <span class="inicial-avatar">{{ strtoupper(substr($nombreUsuario, 0, 1)) }}</span>
            </div>
            <div class="texto-info">
                <h1 class="nombre-usuario">{{ $nombreUsuario }}</h1>
                <button class="btn-editar-perfil" wire:click="exportarClaveRecuperacion" type="button" id="btnExportar">
                    <span class="i-bi-user-edit"></span> Exportar Clave de Recuperación
                </button>
            </div>
        </div>
        <div class="botones-header">
            <a href="#" class="btn-ajustes">
                <span class="icon-[majesticons--bell] icon-large"></span>
            </a>
        </div>
    </div>

    <!-- Menú de navegación estilo tarjetas -->
    <div class="perfil-menu-nuevo">
        <div class="menu-tarjetas">
            <a href="#" class="menu-item" id="miCuenta">
                <span class="icon-[ph--user]"></span>
                <p>Mi Cuenta</p>
            </a>
            <a href="#" class="menu-item" id="misOrdenes">
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

    // Contenido de las tarjetas para "Mi Cuenta" y "Mis Ordenes"
    const contenidoTarjetas = {
        miCuenta: `
            <a href="/editarperfil" class="tarjeta">
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
            <a href="#" class="tarjeta">
                <div class="texto-tarjeta" style="text-align: center;">
                    <span class="icon-[charm--padlock]"></span>
                    <p>Métodos de seguridad</p>
                </div>
            </a>
            <a href="/favoritos" class="tarjeta">
                <div class="texto-tarjeta" style="text-align: center;">
                    <span class="icon-[bi--heart]"></span>
                    <p>Mis favoritos</p>
                </div>
            </a>
        `,
        misOrdenes: `
         <a href="#" class="tarjeta" wire:click="redirigirOrdenes('nuevo')">
            <div class="texto-tarjeta" style="text-align: center;">
                <span class="icon-[hugeicons--package-moving]"></span>
                <p>Nuevo</p>
                <h3>{{ $contadorNuevo }}</h3>
            </div>
        </a>
        <a href="#" class="tarjeta" wire:click="redirigirOrdenes('procesado')">
            <div class="texto-tarjeta" style="text-align: center;">
                <span class="icon-[hugeicons--package-process]"></span>
                <p>En proceso</p>
                <h3>{{ $contadorProceso }}</h3>
            </div>
        </a>
        <a href="#" class="tarjeta" wire:click="redirigirOrdenes('enviado')">
            <div class="texto-tarjeta" style="text-align: center;">
                <span class="icon-[hugeicons--package-open]"></span>
                <p>Enviado</p>
                <h3>{{ $contadorEnviado }}</h3>
            </div>
        </a>
        <a href="#" class="tarjeta" wire:click="redirigirOrdenes('entregado')">
            <div class="texto-tarjeta" style="text-align: center;">
                <span class="icon-[hugeicons--package-delivered]"></span>
                <p>Entregado</p>
                <h3>{{ $contadorEntregado }}</h3>
            </div>
        </a>

        `
    };

    // Función para mostrar el contenido de las tarjetas
    function mostrarContenido(tipo) {
        tarjetasResumen.innerHTML = contenidoTarjetas[tipo];
        tarjetasResumen.style.display = 'flex';
    }

    // Evento para simular el clic en "Mi Cuenta" al cargar la página
    document.addEventListener('DOMContentLoaded', function () {
        mostrarContenido('miCuenta'); // Muestra el contenido de "Mi Cuenta"
    });

    // Añadir event listeners a los botones del menú
    menuItems.miCuenta.addEventListener('click', function (event) {
        event.preventDefault();
        mostrarContenido('miCuenta');
    });

    menuItems.misOrdenes.addEventListener('click', function (event) {
        event.preventDefault();
        mostrarContenido('misOrdenes');
    });
</script>
