<div class="min-h-screen flex flex-col justify-center items-center">
    <h2 class="text-2xl font-semibold mb-4 text-center">EDITAR PERFIL</h2>

    <form wire:submit.prevent="actualizarPerfil" class="w-full max-w-md p-6">
        <div class="mb-4">
            <label for="nombre" class="block">Nombre:</label>
            <input type="text" id="nombre" wire:model="nombre" class="input w-full" placeholder="Ingrese su nombre">
            @error('nombre')
            <div class="contenedor-shake animate-shake">
                <span class="text-red-500">{{ $message }}</span>
            </div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block">Correo Electrónico:</label>
            <input type="email" id="email" wire:model="email" class="input w-full" placeholder="Ingrese su correo electrónico">
            @error('email')
            <div class="contenedor-shake animate-shake">
                <span class="text-red-500">{{ $message }}</span>
            </div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="telefono" class="block">Número de Teléfono:</label>
            <input type="text" id="telefono" wire:model="telefono" class="input w-full" placeholder="Ingrese su número de teléfono" maxlength="8"> <!-- Agregar este campo -->
            @error('telefono')
            <div class="contenedor-shake animate-shake">
                <span class="text-red-500">{{ $message }}</span>
            </div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="current_password" class="block">Contraseña Actual:</label>
            <input type="password" id="current_password" wire:model="current_password" class="input w-full" placeholder="Ingrese su contraseña actual">
            @error('current_password')
            <div class="contenedor-shake animate-shake">
                <span class="text-red-500">{{ $message }}</span>
            </div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="new_password" class="block">Nueva Contraseña:</label>
            <input type="password" id="new_password" wire:model="new_password" class="input w-full" placeholder="Ingrese su nueva contraseña">
            @error('new_password')
            <div class="contenedor-shake animate-shake">
                <span class="text-red-500">{{ $message }}</span>
            </div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="new_password_confirmation" class="block">Confirmar Nueva Contraseña:</label>
            <input type="password" id="new_password_confirmation" wire:model="new_password_confirmation" class="input w-full" placeholder="Confirme su nueva contraseña">
            @error('new_password_confirmation')
            <div class="contenedor-shake animate-shake">
                <span class="text-red-500">{{ $message }}</span>
            </div>
            @enderror
        </div>

        <div class="flex justify-between mt-6">
            <p class="text-sm">
                <a href="/perfil" class="text-sm font-black text-primary hover:text-blue-400 hover:ease-in hover:duration-300">Volver al perfil</a>
            </p>
            <button type="submit" class="btn" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="actualizarPerfil">Guardar</span>
                <span wire:loading wire:target="actualizarPerfil" class="icon-[line-md--loading-loop] h-4 w-4 animate-spin"></span>
            </button>
        </div>
    </form>
</div>
