<div class="qsrp-container mx-auto mt-5">
    <!-- Título centrado -->
    <div class="text-center mb-12 lg:mb-20 mt-12">
        <h2 class="text-5xl font-bold mb-4"><span class="text-primary">Quejas y </span><span class="text-primary">Sugerencias</span></h2>
        <p class="my-7">Tu opinión es fundamental para mejorar. Ayúdanos a ofrecerte un mejor servicio.</p>
    </div>

    <div class="qsrp-card">
        <form wire:submit.prevent="crearQuejaSugerencia" class="form-container mb-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="qsrp-form-group">
                    <label class="qsrp-form-label">Título</label>
                    <input type="text" wire:model="titulo" class="qsrp-form-input" maxlength="30" oninput="updateTitleCharacterCount(this)">
                    <div class="text-right">
                        <span id="titleCharCount" class="text-gray-600">
                            <span id="titleRemainingCount">30</span>/30 caracteres restantes
                        </span>
                    </div>
                    @error('titulo') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="qsrp-form-group">
                    <label class="qsrp-form-label">Tipo</label>
                    <select wire:model="tipo" class="qsrp-form-select">
                        <option value="">Seleccione el tipo</option>
                        <option value="Queja">Queja</option>
                        <option value="Sugerencia">Sugerencia</option>
                    </select>
                    @error('tipo') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="qsrp-form-group">
                <label class="qsrp-form-label">Descripción</label>
                <textarea wire:model="descripcion" class="qsrp-form-textarea" maxlength="500" oninput="updateCharacterCount(this)"></textarea>
                <div class="text-right">
                    <span id="charCount" class="text-gray-600">
                        <span id="remainingCount">500</span>/500 caracteres restantes
                    </span>
                </div>
                @error('descripcion') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="qsrp-submit-button">Enviar</button>
        </form>
    </div>
</div>


<script>
    function updateCharacterCount(textarea) {
        const remainingCount = document.getElementById('remainingCount');
        const maxLength = 500;
        const currentLength = textarea.value.length;

        const charsLeft = maxLength - currentLength;

        remainingCount.innerText = charsLeft;
    }

    function updateTitleCharacterCount(input) {
        const titleRemainingCount = document.getElementById('titleRemainingCount');
        const titleMaxLength = 30;
        const titleCurrentLength = input.value.length;

        const titleCharsLeft = titleMaxLength - titleCurrentLength;

        titleRemainingCount.innerText = titleCharsLeft;
    }

</script>
