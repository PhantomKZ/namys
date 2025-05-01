@php
$current = \Route::currentRouteName() ?? 'home';
$title = isset($title)
    ? (__($title) . ' | NAMYS')
    : 'NAMYS | ' . __('Интернет-магазин одежды');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preload" as="image" href="{{ asset('images/main/namyslogowhite.png') }}">
    <link rel="preload" as="image" href="{{ asset('images/main/namyslogoblack.png') }}">
    <title> {{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
@include('layouts.header')
<div class="min-vh-75">
    @if (session('success') || session('error'))
        <div id="alertMessage" class="alert position-fixed fade show" role="alert" style="bottom: 0; right: 0; z-index: 1050;">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        <script>
            setTimeout(function() {
                var alert = document.getElementById('alertMessage');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                }
            }, 3000);
        </script>
    @endif
    @yield('content')
</div>
@yield('scripts')
@stack('scripts')
@include('layouts.footer')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script>
    const sections = document.querySelectorAll('.lazy-content');

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const section = entry.target;

                setTimeout(() => {
                    section.classList.add('loaded');
                    section.dataset.loaded = 'true';
                }, 200);

                obs.unobserve(section);
            }
        });
    }, {
        rootMargin: '0px 0px -100px 0px',
        threshold: 0.1
    });

    sections.forEach(section => {
        observer.observe(section);
    });
</script>
</body>
</html>
