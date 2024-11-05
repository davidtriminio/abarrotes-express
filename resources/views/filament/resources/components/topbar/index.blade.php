@php
    $titulo_pagina = request()->is('admin') ? 'Inicio' : $titulo_pagina;
    if(!session('titulo_pagina') || !$titulo_pagina){
        session(['titulo_pagina' => env('APP_NAME')]);
    }
@endphp
<div class="page-title">
    <h1 class="text-3xl font-bold">
        <div class="capitalize">
            {{ $titulo_pagina }}
        </div>
    </h1>
</div>
