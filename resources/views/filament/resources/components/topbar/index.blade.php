@php
    $titulo_pagina = request()->is('admin') ? 'Inicio' : $titulo_pagina;
    if(!session('titulo_pagina') || !$titulo_pagina){
        session(['titulo_pagina' => env('APP_NAME')]);
    }
@endphp
<div class="page-title">
    <h1 class="md:text-2xl lg:text-2xl sm:text-xs text-lg">
            {{ $titulo_pagina }}
    </h1>
</div>
