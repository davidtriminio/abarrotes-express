<div>
    <div
        style="background-image: url({{url(asset('imagen/fondo_login.jpeg'))}}); background-size: cover; background-position: center;">
        <div class="flex justify-center items-center h-screen">
            <div class="bg-white rounded-lg shadow-md p-4 md:p-10 mt-5 mb-5"
                 style="width: 550px; min-height: 400px; margin: auto;">
                <h2 class="text-2xl font-semibold mb-4 text-center">Registro</h2>

                <form method="post" wire:submit.prevent="guardar">
                    <div class="mb-3">
                        <label for="name" class="block">Nombre de Usuario</label>
                        <input wire:model="name" type="name" id="register-email"
                               class="input"
                               placeholder="Ingrese un nombre de usuario" required>
                        @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="block">Correo Electrónico</label>
                        <input wire:model="email" type="email" id="login-email"
                               class="input"
                               placeholder="Ingrese su correo electrónico" required>
                        @error('email')
                        <div class="contenedor-shake animate-shake">
                            <span class="text-red-500">{{ $message }}</span>
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="login-password" class="block">Contraseña</label>
                        <input wire:model="password" type="password" id="login-password"
                               class="input"
                               placeholder="Ingrese su contraseña" required>
                        @error('password')
                        <div class="contenedor-shake animate-shake">
                            <span class="text-red-500">{{ $message }}</span>
                        </div>
                        @enderror
                        <br>
                    </div>

                    <div class="text-right mt-2">
                        <p class="mt-4 text-sm text-center">¿Ya tienes una cuenta? <a href="{{ route('login') }}"
                                                                                      class="text-sm text-primary font-black hover:text-blue-400 hover:ease-in hover:duration-300">Iniciar
                                sesión</a></p>
                    </div>

                    <button type="submit" wire:target="guardar"
                            class="btn w-full">
                        <span wire:loading.remove wire:target="guardar">Registrar</span>
                        <span wire:target="guardar" wire:loading
                              class="icon-[line-md--loading-loop] animate-spin"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

