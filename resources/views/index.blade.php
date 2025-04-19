@extends('layouts.app')
@section('content')
    <div class="hero-section">
        <div class="hero-text">
            <h1>NEW DROP</h1>
            <p>C O L L E C T I O N 2025</p>
            <a href="{{route('collection.index')}}" class="glow-button">Перейти</a>
        </div>
    </div>
    <div class="container-fluid">
        <section class="categories mt-4 lazy-content">
            <h2>Категории одежды</h2>
            <p>Минимализм, уникальность, качество и исключительно натуральные материалы</p>
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
                                                <h3>{{ $category->name }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                        @endforeach
            </div>
        </section>

        <!-- Новая коллекция Drop's -->
        <section class="new-drop lazy-content">
            <h2>NEW! NEW! NEW!</h2>
            <h3>DROP'S</h3>
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
                        <button>Добавить в корзину</button>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- О нас -->
        <section class="about-us lazy-content">
            <div class="about-content row">
                <div class="col-md-6">
                    <div class="about-text">
                        <h2>О нас</h2>
                        <p>Мы любим городскую моду и обожаем создавать одежду. Находимся в Павлодаре. Наш бренд NAMYS
                            сфокусирован на тренд, надежность и качество. Доступна доставка по городу.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about-image">
                        <img src="/images/main/aboutme.gif" alt="О нас">
                    </div>
                </div>
            </div>
        </section>

        <!-- Обратная связь -->
        <section class="feedback lazy-content">
            <h2>УЗНАВАЙТЕ О НОВИНКАХ ПЕРВЫМИ!</h2>
            <h3>При первой покупке выдается промокод на 20%!</h3>
            <p>Один раз в месяц мы будем присылать вам информацию о наших последних коллекциях, скидках и акциях.
                Обещаем быть полезными!</p>
            <form class="feedback-form" id="subscribe-form">
                @csrf
                <input type="email" name="email" id="email" placeholder="Ваш E-mail" required>
                <button type="submit">Подписаться</button>
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
                        throw new Error('Ошибка при подписке');
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
