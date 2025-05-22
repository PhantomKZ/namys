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
                        <strong>Номер&nbsp;тел:</strong> {{ $user->phone }}
                    </p>
                @else
                    <p class="profile-phone text-muted">
                        <strong>Номер&nbsp;тел:</strong> не&nbsp;указан
                    </p>
                @endif

                <a href="{{ route('profile.edit') }}" class="edit-profile-btn">Редактировать профиль</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Выйти с учётной записи</button>
                </form>
            </div>

            <div class="profile-stats">
                <div class="stat-item">
                    <a href="{{ route('profile.orders') }}">
                        <div class="stat-number">{{ $user->orders->count() }}</div>
                        <div class="stat-label">Заказов</div>
                    </a>
                </div>
                <div class="stat-item">
                    <div class="stat-number"> {{ $user->favorites->count() }}</div>
                    <div class="stat-label">В избранном</div>
                </div>
            </div>

            <div class="favorites-section">
                <h2 class="section-title">Избранные товары</h2>
                <div class="favorites-grid">
                    @foreach($user->favorites as $item)
                    <a href="{{ route('product.show', $item->id) }}" class="catalog-product-card lazy-content">
                        <div class="product-image-container">
                            <img src="{{ asset($item->mainImage) }}" alt="Футболка AQ Namys" class="product-image">
                        </div>
                        <h3 class="product-title">{{ $item->type}} {{ $item->name }}</h3>
                        <p class="product-price">{{ $item->formattedPrice }}</p>
                        <button class="add-to-cart">Добавить в корзину</button>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
