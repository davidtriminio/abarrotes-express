<div class="contenedor-editar-perfil">
    <div class="tarjeta-formulario">
        <h2 class="titulo-formulario">Editar Perfil</h2>

        <form wire:submit.prevent="actualizarPerfil" class="space-y-6">
            <!-- Campo de Nombre -->
            <div class="espaciado-entrada">
                <label for="nombre" class="text-gray-600">Nombre</label>
                <input
                    type="text"
                    id="nombre"
                    wire:model="nombre"
                    class="input-formulario"
                    placeholder="Ingrese su nombre">
                @error('nombre')
                <span class="texto-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo de Teléfono -->
            <div class="espaciado-entrada">
                <label for="telefono" class="text-gray-600">Número de Teléfono</label>
                <input
                    type="text"
                    id="telefono"
                    wire:model="telefono"
                    class="input-formulario"
                    placeholder="Ingrese su número de teléfono" maxlength="8">
                @error('telefono')
                <span class="texto-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo de Nueva Contraseña -->
            <div class="espaciado-entrada">
                <label for="new_password" class="text-gray-600">Nueva Contraseña</label>
                <input
                    type="password"
                    id="new_password"
                    wire:model="new_password"
                    class="input-formulario"
                    placeholder="Ingrese su nueva contraseña">
                @error('new_password')
                <span class="texto-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo de Confirmar Nueva Contraseña -->
            <div class="espaciado-entrada">
                <label for="new_password_confirmation" class="text-gray-600">Confirmar Nueva Contraseña</label>
                <input
                    type="password"
                    id="new_password_confirmation"
                    wire:model="new_password_confirmation"
                    class="input-formulario"
                    placeholder="Confirme su nueva contraseña">
                @error('new_password_confirmation')
                <span class="texto-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo de Contraseña Actual -->
            <div class="espaciado-entrada">
                <label for="current_password" class="text-gray-600">Contraseña Actual</label>
                <input
                    type="password"
                    id="current_password"
                    wire:model="current_password"
                    class="input-formulario"
                    placeholder="Ingrese su contraseña actual">
                @error('current_password')
                <span class="texto-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Acciones -->
            <div class="flex justify-between items-center mt-8">
                <a href="/perfil" class="enlace-perfil">Volver al perfil</a>
                <button type="submit" class="boton-guardar" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="actualizarPerfil">Guardar Cambios</span>
                    <div wire:loading wire:target="actualizarPerfil" class="icono-carga"></div>
                </button>
            </div>
        </form>
    </div>
</div>
