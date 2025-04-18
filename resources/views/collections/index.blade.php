@extends('layouts.app')
@section('content')
    <img src="{{ asset('images/look.png') }}" alt="Look Collection Banner" class="banner">
    <div class="container-fluid">
        <h1 class="look-title">LOOK COLLECTION</h1>

        <div class="look-grid">
            @foreach($collections as $collection)
                <a href="{{ route('collection.show', $collection->id) }}" class="look-card lazy-content">
                    <img src="{{ asset($collection->mainImage) }}" alt="Look 1') }}" class="look-image">
                    <h3 class="look-name">{{ $collection->name }}</h3>
                </a>
            @endforeach
        </div>
    </div>
@endsection
