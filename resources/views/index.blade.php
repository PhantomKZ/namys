@extends('layouts.app')
@section('content')
    <div class="hero-section">
        <div class="hero-text">
            <h1>{{ __('messages.new_drop') }}</h1>
            <p>{{ __('messages.collection_2025') }}</p>
            <a href="{{route('collection.index')}}" class="glow-button">{{ __('messages.go') }}</a>
        </div>
    </div>
    <div class="container-fluid">
        <section class="categories mt-4 lazy-content">
            <h2>{{ __('messages.categories') }}</h2>
            <p>{{ __('messages.categories_description') }}</p>
            <div class="category-grid">
                @foreach($categories as $category)
                    @php
                        $typeIds = $category->types->pluck('id')->toArray();
                    @endphp
                    @if(count($typeIds) > 0)
                        <a href="{{ route('catalog.index', ['type_id' => $typeIds]) }}">
                            @else
                                <a href="#">
                                    @endif
                                    <div class="category-card">
                                        <div class="category-image">
                                            <img src="{{ asset($category->thumbnail) }}"
                                                 alt="{{ $category->name }} | Namys">
                                            <div class="category-overlay">
                                                <h3>{{ __($category->name) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                        @endforeach
            </div>
        </section>

        <!-- Новая коллекция Drop's -->
        <section class="new-drop lazy-content">
            <h2>{{ __('messages.new_drops_title') }}</h2>
            <h3>{{ __('messages.drops') }}</h3>
            <div class="product-grid">
                @foreach($products as $product)
                    <a href="{{ route('product.show', $product->id) }}" class="product-card">
                        <div class="image-container">
                            <img src="{{ $product->mainImage ? asset($product->mainImage) : '/images/default_main_image.jpg' }}"
                                 alt="{{ $product->name }}" class="main-image">
                            <img src="{{ $product->hoverImage ? asset($product->hoverImage) : '/images/default_hover_image.jpg' }}"
                                 alt="{{ $product->name }}" class="hover-image">
                        </div>
                        <p>{{ $product->title }}</p>
                        <p class="price">{{ $product->formattedPrice }}</p>
                        <button>{{ __('messages.add_to_cart') }}</button>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- О нас -->
        <section class="about-us lazy-content">
            <div class="about-content row">
                <div class="col-md-6">
                    <div class="about-text">
                        <h2>{{ __('messages.about_us') }}</h2>
                        <p>{{ __('messages.about_us_description') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about-image">
                        <img src="/images/main/aboutme.gif" alt="{{ __('messages.about_us') }}">
                    </div>
                </div>
            </div>
        </section>

        <!-- Обратная связь -->
        <section class="feedback lazy-content">
            <h2>{{ __('messages.subscribe_title') }}</h2>
            <h3>{{ __('messages.subscribe_subtitle') }}</h3>
            <p>{{ __('messages.subscribe_description') }}</p>
            <form class="feedback-form" id="subscribe-form">
                @csrf
                <input type="email" name="email" id="email" placeholder="{{ __('messages.your_email') }}" required>
                <button type="submit">{{ __('messages.subscribe') }}</button>
            </form>
        </section>

        <div id="notifications-container"></div>
    </div>
@endsection
@section('scripts')
    <script>
        document.getElementById('subscribe-form').addEventListener('submit', function (e) {
            e.preventDefault();

            var email = document.getElementById('email').value;

            fetch("{{ route('subscribe') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({email: email})
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('{{ __("messages.subscribe_error") }}');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        var notification = document.createElement('div');
                        notification.classList.add('popup-message');
                        notification.classList.add('success');
                        notification.innerHTML = `
                <span>${data.success}</span>
                <button onclick="closePopup(this)" class="close-btn">&times;</button>
            `;
                        document.getElementById('notifications-container').appendChild(notification);
                        setTimeout(function () {
                            notification.style.display = 'none';
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        function closePopup(button) {
            button.parentElement.style.display = 'none';
        }
    </script>
@endsection
