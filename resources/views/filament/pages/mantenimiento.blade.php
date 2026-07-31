<x-filament-panels::page>
    @php
        $isHome = Route::currentRouteName() === 'home';
            if (!$isHome) {
                $titulo_recurso = $this->getTitle();
                session(['titulo_pagina' => $titulo_recurso]);
            }
    @endphp
    @livewire('backup')
</x-filament-panels::page>
