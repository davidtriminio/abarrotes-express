<div>
    <div class="mx-auto" style="max-width: 80rem;">
        <div class="bg-primary text-white p-6"
             style="min-height: 100vh; background-color: #0B0F15; color: #ffffff; padding: 1.5rem;">
            <div class="mx-auto" style="max-width: 80rem; margin: 0 auto;">
                <div class="flex justify-between items-center mb-2"
                     style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <h1 class="text-2xl font-semibold" style="font-size: 1.5rem; font-weight: 600;">Copia de seguridad
                        de base de datos</h1>
                </div>
                <div class="mb-8" style="margin-bottom: 2rem;">
                    <button wire:click="crearBackup()" style="display: flex; align-items: center; gap: 0.5rem; background-color: #3b82f6; color: #ffffff; border-radius: 0.375rem; padding: 0.5rem 1rem; transition: background-color 0.3s;">
                        <!-- Texto visible cuando no está cargando -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><g fill="none"><path d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M8 3v3.5A1.5 1.5 0 0 0 9.5 8h4A1.5 1.5 0 0 0 15 6.5V3h.586A2 2 0 0 1 17 3.586L19.414 6A2 2 0 0 1 20 7.414V19a2 2 0 0 1-2 2h-2v-7a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v7H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm6 11v7H9v-7zM13 3v3h-3V3z"/></g></svg><span wire:loading.remove wire:target="crearBackup">Crear copia de seguridad</span>

                        <!-- SVG de carga que aparece cuando se está procesando -->
                        <svg wire:loading wire:target="crearBackup" xmlns="http://www.w3.org/2000/svg" width="16"
                             height="16" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                               stroke-width="2">
                                <path stroke-dasharray="16" stroke-dashoffset="16" d="M12 3c4.97 0 9 4.03 9 9">
                                    <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.3s" values="16;0"/>
                                    <animateTransform attributeName="transform" dur="1.5s" repeatCount="indefinite"
                                                      type="rotate" values="0 12 12;360 12 12"/>
                                </path>
                                <path stroke-dasharray="64" stroke-dashoffset="64" stroke-opacity=".3"
                                      d="M12 3c4.97 0 9 4.03 9 9c0 4.97 -4.03 9 -9 9c-4.97 0 -9 -4.03 -9 -9c0 -4.97 4.03 -9 9 -9Z">
                                    <animate fill="freeze" attributeName="stroke-dashoffset" dur="1.2s" values="64;0"/>
                                </path>
                            </g>
                        </svg>
                    </button>
                </div>
                @foreach($backups as $backup)
                    <div class="grid grid-cols-2 gap-4 items-center group "
                         style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; align-items: center; background-color: #1A1F26; border-radius: 0.5rem; padding: 0.75rem 1rem; transition: background-color 0.3s; margin-bottom: 1rem;">
                        <div class="flex items-center gap-3" style="display: flex; align-items: center; gap: 0.75rem;">
                            <span class="text-sm"
                                  style="font-size: 0.875rem; color: #ffffff;">{{ basename($backup) }}</span>
                        </div>
                        <div class="flex justify-end items-center gap-3"
                             style="display: flex; justify-content: flex-end; align-items: center; gap: 0.75rem;">
                            <button wire:click="descargarBackup('{{ $backup }}')" class="flex items-center gap-2"
                                    style="display: flex; align-items: center; gap: 0.5rem; background-color: #38A169; color: #ffffff; border-radius: 0.375rem; padding: 0.5rem 1rem; transition: background-color 0.3s;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                    <mask id="IconifyId1938054ad1f21d80a1">
                                        <g fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round"
                                           stroke-width="2">
                                            <path stroke-dasharray="64" stroke-dashoffset="64"
                                                  d="M7 19h11c2.21 0 4 -1.79 4 -4c0 -2.21 -1.79 -4 -4 -4h-1v-1c0 -2.76 -2.24 -5 -5 -5c-2.42 0 -4.44 1.72 -4.9 4h-0.1c-2.76 0 -5 2.24 -5 5c0 2.76 2.24 5 5 5Z">
                                                <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.6s"
                                                         values="64;0"/>
                                                <set fill="freeze" attributeName="opacity" begin="0.7s" to="0"/>
                                            </path>
                                            <g fill="#fff" stroke="none" opacity="0">
                                                <circle cx="12" cy="10" r="6"/>
                                                <rect width="9" height="8" x="8" y="12"/>
                                                <rect width="15" height="12" x="1" y="8" rx="6"/>
                                                <rect width="13" height="10" x="10" y="10" rx="5"/>
                                                <set fill="freeze" attributeName="opacity" begin="0.7s" to="1"/>
                                            </g>
                                            <g fill="#000" fill-opacity="0" stroke="none">
                                                <circle cx="12" cy="10" r="4"/>
                                                <rect width="9" height="6" x="8" y="12"/>
                                                <rect width="11" height="8" x="3" y="10" rx="4"/>
                                                <rect width="9" height="6" x="12" y="12" rx="3"/>
                                                <set fill="freeze" attributeName="fill-opacity" begin="0.7s" to="1"/>
                                                <animate fill="freeze" attributeName="opacity" begin="0.7s" dur="0.5s"
                                                         values="1;0"/>
                                            </g>
                                            <g fill="#000" stroke="none">
                                                <path d="M10 9h4v0h-4z">
                                                    <animate fill="freeze" attributeName="d" begin="1.3s" dur="0.2s"
                                                             values="M10 9h4v0h-4z;M10 9h4v5h-4z"/>
                                                </path>
                                                <path d="M7 13h10l-5 0z">
                                                    <animate fill="freeze" attributeName="d" begin="1.5s" dur="0.1s"
                                                             values="M7 13h10l-5 0z;M7 13h10l-5 5z"/>
                                                </path>
                                            </g>
                                        </g>
                                    </mask>
                                    <rect width="24" height="24" fill="currentColor"
                                          mask="url(#IconifyId1938054ad1f21d80a1)"/>
                                </svg>
                                <span wire:loading.remove wire:target="descargarBackup('{{ $backup }}')">Descargar</span>
                                <span wire:loading wire:target="descargarBackup('{{ $backup }}')">Descargando...</span>
                            </button>

                            <!-- Botón de Eliminar -->
                            <button wire:click="borrarBackup('{{ $backup }}')" class="flex items-center gap-2"
                                    style="display: flex; align-items: center; gap: 0.5rem; background-color: #E53E3E; color: #ffffff; border-radius: 0.375rem; padding: 0.5rem 1rem; transition: background-color 0.3s;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                    <path fill="currentColor" fill-rule="evenodd"
                                          d="m18.412 6.5l-.801 13.617A2 2 0 0 1 15.614 22H8.386a2 2 0 0 1-1.997-1.883L5.59 6.5H3.5v-1A.5.5 0 0 1 4 5h16a.5.5 0 0 1 .5.5v1zM10 2.5h4a.5.5 0 0 1 .5.5v1h-5V3a.5.5 0 0 1 .5-.5M9 9l.5 9H11l-.4-9zm4.5 0l-.5 9h1.5l.5-9z"/>
                                </svg>
                                <span><span wire:loading.remove wire:target="borrarBackup('{{ $backup }}')">Eliminar</span>
                                <span wire:loading wire:target="borrarBackup('{{ $backup }}')">Eliminando...</span></span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
