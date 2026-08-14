<x-filament-panels::page
    @class([
        'fi-resource-list-records-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])
>

    @php
        $isHome = Route::currentRouteName() === 'home';
        $slug = $this->getResource()::getSlug();
        $permiso = 'crear:' . $slug;
        if (!$isHome) {
            $slug = $this->getResource()::getSlug();
            $titulo_recurso = $this->getResource()::getNavigationLabel();
            session(['titulo_pagina' => $titulo_recurso]);
        } else {
            session(['titulo_pagina' => 'Inicio']);
        }
    @endphp
    {{--</div>--}}
    <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="justify-content-start capitalize">
            <x-filament::breadcrumbs :breadcrumbs="[
    '/admin' => 'Inicio',
    $slug => $this->getResource()::getSlug()
    ]"/>
        </div>
    <div class="flex h-10 items-center gap-2 justify-content-end justify-end">
        @if($this->getAction('plantilla') && $this->getAction('plantilla')->isVisible())
            {{ $this->getAction('plantilla') }}
        @endif
        @if($this->getAction('importar') && $this->getAction('importar')->isVisible())
            {{ $this->getAction('importar') }}
        @endif
        @if($this->getAction('exportar') && $this->getAction('exportar')->isVisible())
            {{ $this->getAction('exportar') }}
        @endif
        @if($this->getAction('Crear') && $this->getAction('Crear')->isVisible())
            {{ $this->getAction('Crear') }}
        @endif
    </div>
    </div>
    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs/>
        {{ $this->table }}
    </div>
</x-filament-panels::page>
