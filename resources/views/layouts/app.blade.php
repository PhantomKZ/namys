<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
@include('layouts.header')
<div class="grid-vh-75">
    @yield('content')
</div>
@yield('scripts')
@include('layouts.footer')
<script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
