@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <img src="{{ asset('images/novelty.jpg') }}" alt="Новинки Banner" class="banner">
        <h1 class="nov-title">НОВИНКИ</h1>
        @include('components.catalog')
    </div>
@endsection
