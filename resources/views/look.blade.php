@extends('layouts.app')
@section('content')
    <img src="{{ asset('images/look.png') }}" alt="Look Collection Banner" class="banner">
    <div class="container-fluid">
        <h1 class="look-title">LOOK COLLECTION</h1>

        <div class="look-grid">
            <a href="product/look1.html" class="look-card">
                <img src="{{ asset('images/look/look1.jpg') }}" alt="Look 1') }}" class="look-image">
                <h3 class="look-name">STREET LOOK</h3>
            </a>
            <a href="product/look2.html" class="look-card">
                <img src="{{ asset('images/look/look2.jpg') }}" alt="Look 2') }}" class="look-image">
                <h3 class="look-name">COMFORT LOOK</h3>
            </a>
            <a href="product/look3.html" class="look-card">
                <img src="{{ asset('images/look/look3.jpg') }}" alt="Look 3') }}" class="look-image">
                <h3 class="look-name">URBAN LOOK</h3>
            </a>
            <a href="product/look4.html" class="look-card">
                <img src="{{ asset('images/look/look4.jpg') }}" alt="Look 4') }}" class="look-image">
                <h3 class="look-name">KAWAII LOOK</h3>
            </a>
        </div>
    </div>
@endsection
