<div class="contenedor-editar-perfil">

    <div class="text-center mb-12 lg:mb-20 mt-12">
        <h2 class="text-5xl font-bold mb-4"><span class="text-primary">Editar </span><span class="text-primary">Perfil</span></h2>
        <p class="my-7">Actualiza tus datos personales y cambia tu contraseña de forma segura.</p>
    </div>


    <div class="tarjeta-formulario">
        <!-- Formulario -->
        <form wire:submit.prevent="actualizarPerfil" class="space-y-6">
            <!-- Campo de Nombre -->
            <div class="espaciado-entrada">
                <label for="nombre" class="text-gray-600">Nombre</label>
                <input
                    type="text"
                    id="nombre"
                    wire:model="nombre"
                    class="input-formulario"
                    placeholder="Ingrese su nombre" maxlength="60">
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
                <a href="{{ route('perfil') }}" class="enlace-perfil">Volver al perfil</a>
                <button type="submit" class="boton-guardar" wire:loading.attr="disabled">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
