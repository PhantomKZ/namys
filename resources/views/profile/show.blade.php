@extends('layouts.app')
@section('content')
    <div class="profile-section">
        <div class="container">
            <div class="profile-header">
                <img src="{{ $user->avatar ? asset($user->avatar) : asset('images/avatar.png') }}" alt="Фото профиля" class="profile-avatar">
                <h1 class="profile-name">{{ $user->name }}</h1>
                <p class="profile-email">{{ $user->email }}</p>
                @if($user->phone)
                    <p class="profile-phone">
                        <strong>{{ __('messages.phone_number') }}:</strong> {{ $user->phone }}
                    </p>
                @else
                    <p class="profile-phone text-muted">
                        <strong>{{ __('messages.phone_number') }}:</strong> {{ __('messages.not_specified') }}
                    </p>
                @endif

                <a href="{{ route('profile.edit') }}" class="edit-profile-btn">{{ __('messages.edit_profile') }}</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">{{ __('messages.logout') }}</button>
                </form>
            </div>

            <div class="profile-stats">
                <div class="stat-item">
                    <a href="{{ route('profile.orders') }}">
                        <div class="stat-number">{{ $user->orders->count() }}</div>
                        <div class="stat-label">{{ __('messages.orders') }}</div>
                    </a>
                </div>
                <div class="stat-item">
                    <div class="stat-number"> {{ $user->favorites->count() }}</div>
                    <div class="stat-label">{{ __('messages.favorites') }}</div>
                </div>
            </div>

            <div class="favorites-section">
                <h2 class="section-title">{{ __('messages.favorites') }}</h2>
                <div class="favorites-slider">
                    <div class="favorites-track">
                        @foreach($user->favorites as $item)
                        <div class="favorite-slide">
                            <a href="{{ route('product.show', $item->id) }}" class="catalog-product-card lazy-content">
                                <div class="product-image-container">
                                    <img src="{{ asset($item->mainImage) }}" alt="{{ $item->name }}" class="product-image">
                                </div>
                                <h3 class="product-title">{{ $item->type}} {{ $item->name }}</h3>
                                <p class="product-price">{{ $item->formattedPrice }}</p>
                                <button class="add-to-cart">{{ __('messages.add_to_cart') }}</button>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
