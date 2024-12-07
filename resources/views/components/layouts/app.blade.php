<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Abarrotes Express' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="stylesheet" href="{{url(asset('css/styles/custom.css'))}}">
    <link rel="stylesheet" href="{{url(asset('css/styles/styles.css'))}}">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <!--@filamentStyles-->
</head>
<body class="antialiased">
@livewire('notifications')
@livewire('complementos.navbar')
<main>
    {{ $slot }}
</main>
@livewire('complementos.footer')
@filamentScripts
@livewireScripts
<script src="{{url(asset('js/sweetalert2@11.js'))}}"></script>
<script>
    
</script>
<x-livewire-alert::scripts />
</body>
</html>
