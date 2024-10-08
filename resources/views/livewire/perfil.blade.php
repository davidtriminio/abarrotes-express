<div class="perfil-contenedor-nuevo">
    <!-- Cabecera principal con fondo azul marino -->
    <div class="perfil-cabecera">
        <div class="perfil-info">
            <img src="avatar.png" alt="Avatar del usuario" class="avatar">
            <div class="texto-info">
                <h1 class="nombre-usuario">[Nombre del Usuario]</h1>
                <a href="#" class="btn-editar-perfil">
                    <span class="i-bi-user-edit"></span> Editar Perfil
                </a>
            </div>
        </div>
        <div class="botones-header">
            <a href="#" class="btn-notificaciones">
                <span class="i-bi-bell"></span> Notificaciones
            </a>
            <a href="#" class="btn-ajustes">
                <span class="i-bi-gear"></span> Ajustes
            </a>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="tarjetas-resumen">
        <a href="#" class="tarjeta">
            <div class="texto-tarjeta" style="text-align: center;">
                <span class="icon-[icon-park-outline--order]"></span>
                <p>Pedidos en curso</p>
                <h3>3</h3>
            </div>
        </a>
        
        <a href="#" class="tarjeta">
       
            <div class="texto-tarjeta" style="text-align: center;">
                <span class="icon-[ci--ticket-voucher]"></span>
                <p>Mis Cupones</p>
                <h3>5</h3>
            </div>
        </a>
        <a href="/ordenes" class="tarjeta">
            <div class="texto-tarjeta" style="text-align: center;">
                <span class="icon-[bi--truck]"></span>
                <p>Mis ordenes</p>
                <h3>5</h3>
            </div>
        </a>
        <a href="/favoritos" class="tarjeta">
            <div class="texto-tarjeta" style="text-align: center;">
                <span class="icon-[bi--heart]"></span>
                <p>Favoritos</p>
                <h3>{{ $contadorFavoritos }}</h3>
            </div>
        </a>

    </div>

    <!-- Menú de navegación estilo tarjetas -->
    <div class="perfil-menu-nuevo">
        <h2>Mi Cuenta</h2>
        <div class="menu-tarjetas">
            <a href="#" class="menu-item">
                <p><span class="i-bi-user"></span> Información Personal</p>
            </a>
            <a href="#" class="menu-item">
                <p><span class="i-bi-history"></span> Historial de Compras</p>
            </a>
            <a href="#" class="menu-item">
                <p><span class="i-bi-credit-card"></span> Métodos de Pago</p>
            </a>
            <a href="#" class="menu-item">
                <p><span class="i-bi-truck"></span> Seguimiento de Pedidos</p>
            </a>
            <a href="#" class="menu-item">
                <p><span class="i-bi-lock"></span> Seguridad de la Cuenta</p>
            </a>
        </div>
    </div>
</div>
