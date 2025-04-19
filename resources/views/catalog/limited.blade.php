@extends('layouts.app')
@section('content')
    <img src="{{ asset('images/limited.png') }}" alt="Limited Edition Banner" class="banner">
    <div class="container-fluid">
        <h1 class="le-title">LIMITED EDITION</h1>

       @include('components.catalog')
    </div>
@endsection
