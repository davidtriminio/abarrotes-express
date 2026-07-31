<x-filament-panels::page>
    @php
        $isHome = Route::currentRouteName() === 'home';
        if ($this->getTitle()){
         $titulo_detalles = $this->getTitle();
        }
        if (!$isHome) {
        $titulo_recurso = $titulo_detalles;
            session(['titulo_pagina' => $titulo_recurso]);
        } else {
            session(['titulo_pagina' => 'Inicio']);
      }
    @endphp
<x-filament-widgets::widgets

        :data="
            [
                ...(property_exists($this, 'filters') ? ['filters' => $this->filters] : []),
                ...$this->getWidgetData(),
            ]
        "
        :widgets="$this->getVisibleWidgets()"
    />
</x-filament-panels::page>
