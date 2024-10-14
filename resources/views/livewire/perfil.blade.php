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
                    <span class="i-bi-user-edit"></span>Exportar Clave de Recuperación
                </button>
            </div>
        </div>
        <div class="botones-header">
            <a href="#" class="btn-ajustes">
                <span class="icon-[majesticons--bell] icon-large"></span>
            </a>
        </div>
    </div>

    <!-- Menú de navegación estilo tarjetas (Movido arriba) -->
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

    <div class="tarjetas-resumen " id="tarjetasResumen">
        <!-- Resumen de tarjetas -->
    </div>
</div>

<script>
    const miCuentaBtn = document.getElementById('miCuenta');
    const misOrdenesBtn = document.getElementById('misOrdenes');
    const tarjetasResumen = document.getElementById('tarjetasResumen');

    // Simulación de clic en el botón "Mi Cuenta" al cargar la página
    document.addEventListener('DOMContentLoaded', function () {
        miCuentaBtn.click(); // Simular clic en el botón "Mi Cuenta" al cargar la página
    });

    miCuentaBtn.addEventListener('click', function (event) {
        event.preventDefault();

        tarjetasResumen.innerHTML = `
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
        `;
        tarjetasResumen.style.display = 'flex';
    });

    misOrdenesBtn.addEventListener('click', function (event) {
        event.preventDefault();

        tarjetasResumen.innerHTML = `
            <a href="#" class="tarjeta">
                <div class="texto-tarjeta" style="text-align: center;">
                    <span class="icon-[hugeicons--package-moving]"></span>
                    <p>Nuevo</p>
                    <h3>{{$contadorNuevo}}</h3>
                </div>
            </a>
            <a href="#" class="tarjeta">
                <div class="texto-tarjeta" style="text-align: center;">
                    <span class="icon-[hugeicons--package-process]"></span>
                    <p>En proceso</p>
                    <h3>{{$contadorProceso}}</h3>
                </div>
            </a>
            <a href="#" class="tarjeta">
                <div class="texto-tarjeta" style="text-align: center;">
                    <span class="icon-[hugeicons--package-open]"></span>
                    <p>Entregado</p>
                    <h3>{{$contadorEntregado}}</h3>
                </div>
            </a>
            <a href="#" class="tarjeta">
                <div class="texto-tarjeta" style="text-align: center;">
                    <span class="icon-[hugeicons--package-delivered]"></span>
                    <p>Terminado</p>
                    <h3>{{$contadorTerminado}}</h3>
                </div>
            </a>
        `;
        tarjetasResumen.style.display = 'flex';
    });
</script>


