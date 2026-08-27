@props([

    'lang'     =>   'en',
    'title'    =>   '',
    'page'     =>   '',
    'external' =>   '',

])


<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="icon" type="image/x-icon" href="/favicon.ico"> --}}
    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body {{ $attributes }} data-page="{{ $page }}">
    {{ $external }}
    {{ $slot }}
    @livewireScripts
</body>
</html>
