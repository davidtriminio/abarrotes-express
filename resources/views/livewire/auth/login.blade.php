<div class="bg-fondo min-h-screen flex justify-center items-center" style="background-image: url({{url(asset('imagen/fondo_login.jpeg'))}})">
    <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold mb-4 text-center">Iniciar Sesión ó Regístrate</h2>

        <form wire:submit.prevent="save">
            <div class="mb-4">
                <label for="login-password" class="block">Correo Electronico</label>
                <input wire:model="email" type="email" id="login-email" class="input" placeholder="Ingrese su correo electrónico" required>
                @error('email')
                <div class="contenedor-shake animate-shake">
                    <span class="text-red-500">{{ $message }}</span>
                </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="login-password" class="block">Contraseña</label>
                <input wire:model="password" type="password" id="login-password" class="input" placeholder="Ingrese su contraseña" required>
                @error('password')
                <div class="contenedor-shake animate-shake">
                    <span class="text-red-500 ">{{ $message }}</span>
                </div>
                @enderror
            </div>

            <!-- Enlace de "¿Olvidaste tu contraseña?" alineado a la derecha -->
            <div class="mb-4 flex justify-end">
                <a href="{{route('verificarclave')}}" class="text-sm text-primary hover:text-blue-400 hover:ease-in hover:duration-300">¿Olvidaste tu contraseña?</a>
            </div>

            @if (session()->has('error'))
                <div class="mb-4 animate-shake">
                    <span class="text-red-500 ">{{ session('error') }}</span>
                </div>
            @endif

            <button type="submit" class="btn w-full" wire:target="save">
                <span wire:loading.remove wire:target="save">Iniciar Sesión</span>
                <span wire:loading wire:target="save" class="icon-[line-md--loading-loop] h-4 w-4 animate-spin"></span>
            </button>
        </form>

        <p class="mt-4 text-sm text-center">¿No tienes una cuenta? <a href="{{ route('registro') }}" class="text-sm font-black text-primary hover:text-blue-400 hover:ease-in hover:duration-300">Regístrate</a></p>
    </div>
</div>
