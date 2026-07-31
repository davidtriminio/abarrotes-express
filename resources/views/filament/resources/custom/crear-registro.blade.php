<x-filament-panels::page
    @class([
        'fi-resource-create-record-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])
>

    <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="justify-content-start capitalize">
            <x-filament::breadcrumbs :breadcrumbs="[
    '/admin' => 'Inicio',
    '/admin/' . $this->getResource()::getSlug() => $this->getResource()::getSlug(),
]"/>
        </div>
        <div class="flex h-10 justify-end space-x-6">
            <span class="mx-3 p-1">{{$this->getAction('Regresar')}}</span>
        </div>
    </div>

    @php
        $isHome = Route::currentRouteName() === 'home';
            if (!$isHome) {
                $titulo_recurso = $this->getTitle();
                session(['titulo_pagina' => $titulo_recurso]);
            }
    @endphp

    <x-filament-panels::form
        id="form"
        :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
        wire:submit="create"
    >
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <x-filament-panels::page.unsaved-data-changes-alert />
</x-filament-panels::page>
