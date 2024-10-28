<div class="bg-fondo min-h-screen flex justify-center items-center" style="background-image: url({{ url(asset('imagen/fondo_login.jpeg')) }})">
    <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold mb-4 text-center">Cambiar Contraseña</h2>

        <form wire:submit.prevent="cambiarContrasena">
            <div class="mb-4">
                <label for="new_password" class="block">Nueva Contraseña</label>
                <input wire:model="new_password" type="password" id="new_password" class="input" placeholder="Ingrese su nueva contraseña" required>
                @error('new_password')
                <div class="contenedor-shake animate-shake">
                    <span class="text-red-500">{{ $message }}</span>
                </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="block">Confirmar Contraseña</label>
                <input wire:model="confirm_password" type="password" id="confirm_password" class="input" placeholder="Confirme su nueva contraseña" required>
                @error('confirm_password')
                <div class="contenedor-shake animate-shake">
                    <span class="text-red-500">{{ $message }}</span>
                </div>
                @enderror
            </div>

            @if (session()->has('error'))
                <div class="mb-4 animate-shake">
                    <span class="text-red-500">{{ session('error') }}</span>
                </div>
            @endif

            <button type="submit" class="btn w-full" wire:target="cambiarContrasena">
                <span wire:loading.remove wire:target="cambiarContrasena">Actualizar Contraseña</span>
                <span wire:loading wire:target="cambiarContrasena" class="icon-[line-md--loading-loop] h-4 w-4 animate-spin"></span>
            </button>
        </form>

        <p class="mt-4 text-sm text-center">¿Recordaste tus datos? <a href="{{ route('login') }}" class="text-sm font-black text-primary hover:text-blue-400 hover:ease-in hover:duration-300">Inicia Sesión</a></p>
    </div>
</div>
