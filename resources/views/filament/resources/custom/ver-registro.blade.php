<x-filament-panels::page
    @class([
        'fi-resource-view-record-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
        'fi-resource-record-' . $record->getKey(),
    ])
>
    @php
        $isHome = Route::currentRouteName() === 'home';
        $slug = rtrim($this->getResource()::getSlug(), '/');

        if ($this->getTitle()){
         $titulo_detalles = $this->getTitle();
        }
        $permiso = 'crear:' . $slug;
        if (!$isHome) {
        $titulo_recurso = $titulo_detalles;
            session(['titulo_pagina' => $titulo_recurso]);
        } else {
            session(['titulo_pagina' => 'Inicio']);
      }
    @endphp

    <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="justify-content-start capitalize">
            <x-filament::breadcrumbs :breadcrumbs="[
    '/admin' => 'Inicio',
    '/admin/' . $slug => $slug,
]"/>
        </div>
        <div class="flex h-10 justify-end space-x-6">
            <span class="mx-3 p-1">{{$this->getAction('Regresar')}}</span>
            @if($this->getAction('Editar')->isVisible())
                <span class="mx-2 p-1">{{ $this->getAction('Editar') }}</span>
            @endif
            @if($this->getAction('Crear') && $this->getAction('Crear')->isVisible())
                <span class="mx-2 p-1">{{ $this->getAction('Crear') }}</span>
            @endif
            @if($this->getAction('Borrar') && $this->getAction('Borrar')->isVisible())
                <span class="mx-2 p-1">{{ $this->getAction('Borrar') }}</span>
            @endif
        </div>
    </div>
    @php
        $relationManagers = $this->getRelationManagers();
        $hasCombinedRelationManagerTabsWithContent = $this->hasCombinedRelationManagerTabsWithContent();
    @endphp

    @if ((! $hasCombinedRelationManagerTabsWithContent) || (! count($relationManagers)))
        @if ($this->hasInfolist())
            {{ $this->infolist }}
        @else
            <div
                wire:key="{{ $this->getId() }}.forms.{{ $this->getFormStatePath() }}"
            >
                {{ $this->form }}
            </div>
        @endif
    @endif
    {{--Configuración para el título--}}

    @if (count($relationManagers))
        <x-filament-panels::resources.relation-managers
            :active-locale="isset($activeLocale) ? $activeLocale : null"
            :active-manager="$this->activeRelationManager ?? ($hasCombinedRelationManagerTabsWithContent ? null : array_key_first($relationManagers))"
            :content-tab-label="$this->getContentTabLabel()"
            :content-tab-icon="$this->getContentTabIcon()"
            :content-tab-position="$this->getContentTabPosition()"
            :managers="$relationManagers"
            :owner-record="$record"
            :page-class="static::class"
        >
            @if ($hasCombinedRelationManagerTabsWithContent)
                <x-slot name="content">
                    @if ($this->hasInfolist())
                        {{ $this->infolist }}
                    @else
                        {{ $this->form }}
                    @endif
                </x-slot>
            @endif
        </x-filament-panels::resources.relation-managers>
    @endif
</x-filament-panels::page>
