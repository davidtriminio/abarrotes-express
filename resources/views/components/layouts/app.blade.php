<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Abarrotes Express' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="stylesheet" href="{{url(asset('css/styles/custom.css'))}}">
    <link rel="stylesheet" href="{{url(asset('css/styles/styles.css'))}}">
</head>
<body>
@livewire('complementos.navbar')
<main>
    {{ $slot }}
</main>
@livewire('complementos.footer')
@livewireScripts
<script src="{{url(asset('js/sweetalert2@11.js'))}}"></script>
<x-livewire-alert::scripts />
</body>
</html>
