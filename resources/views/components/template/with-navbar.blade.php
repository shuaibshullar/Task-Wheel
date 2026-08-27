@props([

    'lang'     =>   'en',
    'title'    =>   '',
    'page'     =>   '',
    'external' =>   '',

])


<!DOCTYPE html>
<html lang="{{ $lang }}" @if($errors->any()) class="overflow-hidden" @endif>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="icon" type="image/x-icon" href="/favicon.ico"> --}}
    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body {{ $attributes }} data-page="{{ $page }}">
    <x-navbar />
    <div class="relative z-5000 contents">
        {{ $external }}
    </div>

    <div class="relative z-0">
        {{ $slot }}
    </div>
</body>
</html>
