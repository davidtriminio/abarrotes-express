<x-filament-panels::page
    @class([
        'fi-resource-edit-record-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
        'fi-resource-record-' . $record->getKey(),
    ])
>
    @capture($form)
    <x-filament-panels::form
        id="form"
        :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
        wire:submit="save"
    >
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
    @endcapture

    @php
        $isHome = Route::currentRouteName() === 'home';
        $recordId = $this->getRecord()->getKey();

        $slug = rtrim($this->getResource()::getSlug(), '/view/' . $recordId);
        $permiso = 'crear:' . $slug;

        if (!$isHome) {
            if ($slug == 'categorias'){
             $titulo_recurso = 'Editando: ' . $this->getRecord()->nombre;
             $titulo_bread = $this->getRecord()->nombre;
            }
            else if ($slug == 'ordenes'){
            $titulo_recurso = 'Editando Orden: ' . $this->getRecord()->id;
            $titulo_bread = 'Orden: ' . $this->getRecord()->id;
            }
            else if($slug == 'cupones'){
             $titulo_recurso = 'Editando Cupón: ' . $this->getRecord()->codigo;
             $titulo_bread = $this->getRecord()->codigo;
            }
            else if ($slug == 'usuarios'){
             $titulo_recurso = 'Editando Usuario: ' . $this->getRecord()->name;
             $titulo_bread = $this->getRecord()->name;
            }
            else{
             $titulo_recurso = 'Editando: ' . $this->getRecord()->nombre;
             $titulo_bread = $this->getRecord()->nombre;
            }
            session(['titulo_pagina' => $titulo_recurso]);
        } else {
            session(['titulo_pagina' => 'Inicio']);
        }
    @endphp

    <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="justify-content-start capitalize">
            <x-filament::breadcrumbs :breadcrumbs="[
    '/admin' => 'Inicio',
    '/admin/' . $slug =>  $slug,
    '/admin/' . $slug . '/' . $recordId . '/view' => $titulo_bread,
            ]"/>
        </div>

        <div class="flex h-10 justify-end space-x-6">
            <span class="mx-3 p-1">{{$this->getAction('Regresar')}}</span>
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
        {{ $form() }}
    @endif

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
                    {{ $form() }}
                </x-slot>
            @endif
        </x-filament-panels::resources.relation-managers>
    @endif

    <x-filament-panels::page.unsaved-data-changes-alert/>
</x-filament-panels::page>
