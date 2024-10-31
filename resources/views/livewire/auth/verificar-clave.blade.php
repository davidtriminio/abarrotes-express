<div class="bg-fondo min-h-screen flex justify-center items-center" style="background-image: url({{ url(asset('imagen/fondo_login.jpeg')) }})">
    <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold mb-4 text-center">¿Cómo quieres recuperar tu cuenta?</h2>

        <!-- Selector de método de recuperación -->
        <div class="mb-4 flex justify-center space-x-8">
            <label class="inline-flex items-center mr-4">
                <input type="radio" name="recovery_method" value="email" onclick="toggleRecoveryFields()" checked>
                <span class="ml-2">Correo Electrónico</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" name="recovery_method" value="recovery_key" onclick="toggleRecoveryFields()">
                <span class="ml-2">Clave de Recuperación</span>
            </label>
        </div>

        <!-- Formulario -->
        <form id="recovery-form">
            <!-- Campo de Correo Electrónico -->
            <div id="email-field" class="mb-4">
                <label for="email" class="block">Correo Electrónico</label>
                <input wire:model="email" type="email" id="email" class="input" placeholder="Ingrese su correo electrónico">
                @error('email')
                <div class="contenedor-shake animate-shake">
                    <span class="text-red-500">{{ $message }}</span>
                </div>
                @enderror
            </div>

            <!-- Campo de Clave de Recuperación -->
            <div id="recovery-key-field" class="mb-4 hidden">
                <label for="recovery-key" class="block">Clave de Recuperación</label>
                <input wire:model="recovery_key" type="text" id="recovery-key" class="input" placeholder="Ingrese su clave de recuperación" required maxlength="30">
                @error('recovery_key')
                <div class="contenedor-shake animate-shake">
                    <span class="text-red-500">{{ $message }}</span>
                </div>
                @enderror
            </div>

            <!-- Mensaje de Error -->
            @if (session()->has('error'))
                <div class="mb-4 animate-shake">
                    <span class="text-red-500">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Botón de Envío -->
            <button type="button" class="btn w-full" onclick="submitRecoveryForm()" id="submit-button">
                <span wire:loading.remove id="button-text">Enviar</span>
                <span wire:loading class="icon-[line-md--loading-loop] h-4 w-4 animate-spin"></span>
            </button>
        </form>

        <p class="mt-4 text-sm text-center">¿Recordaste tu contraseña? <a href="{{ route('login') }}" class="text-sm font-black text-primary hover:text-blue-400 hover:ease-in hover:duration-300">Inicia Sesión</a></p>
    </div>
</div>

<!-- Script para Alternar Campos y Enviar -->
<script>
    function toggleRecoveryFields() {
        const emailField = document.getElementById('email-field');
        const recoveryKeyField = document.getElementById('recovery-key-field');
        const recoveryMethod = document.querySelector('input[name="recovery_method"]:checked').value;
        const submitButtonText = document.getElementById('button-text');


        if (recoveryMethod === 'email') {
            emailField.classList.remove('hidden');
            recoveryKeyField.classList.add('hidden');
            submitButtonText.textContent = 'Enviar';
        } else {
            emailField.classList.add('hidden');
            recoveryKeyField.classList.remove('hidden');
            submitButtonText.textContent = 'Verificar Clave';
        }
    }

    function submitRecoveryForm() {
        const recoveryMethod = document.querySelector('input[name="recovery_method"]:checked').value;

        if (recoveryMethod === 'email') {
        @this.call('verificarCorreo');
        } else {
        @this.call('verificarClave');
        }
    }


    document.addEventListener('DOMContentLoaded', function() {
        toggleRecoveryFields();
    });
</script>
