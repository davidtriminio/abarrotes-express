<div class="bg-fondo min-h-screen flex justify-center items-center" style="background-image: url({{ url(asset('imagen/fondo_login.jpeg')) }})">
    <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold mb-4 text-center">Verificar Clave de Recuperación</h2>

        <form wire:submit.prevent="verificarClave">
            <div class="mb-4">
                <label for="email" class="block">Correo Electrónico</label>
                <input wire:model="email" type="email" id="email" class="input" placeholder="Ingrese su correo electrónico" required>
                @error('email')
                <div class="contenedor-shake animate-shake">
                    <span class="text-red-500">{{ $message }}</span>
                </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="recovery-key" class="block">Clave de Recuperación</label>
                <input wire:model="recovery_key" type="text" id="recovery-key" class="input" placeholder="Ingrese su clave de recuperación" required maxlength="30">
                @error('recovery_key')
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

            <button type="submit" class="btn w-full" wire:target="verificarClave">
                <span wire:loading.remove wire:target="verificarClave">Verificar Clave</span>
                <span wire:loading wire:target="verificarClave" class="icon-[line-md--loading-loop] h-4 w-4 animate-spin"></span>
            </button>
        </form>

        <p class="mt-4 text-sm text-center">¿Recordaste tu contraseña? <a href="{{ route('login') }}" class="text-sm font-black text-primary hover:text-blue-400 hover:ease-in hover:duration-300">Inicia Sesión</a></p>
    </div>
</div>
