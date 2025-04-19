@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="basket-title mt-3">Корзина</h1>
            @if(!$items->isEmpty())
        <div class="basket-content">
            <div class="d-flex flex-column gap-2">
                <div class="basket-items">
                    @foreach($items as $item)
                    <div class="basket-item">
                        <div class="item-image">
                            <a href="{{ route('product.show', $item->product->id) }}">
                                <img src="{{ asset($item->product->mainImage) }}" alt="Футболка AQ NAMYS">
                            </a>
                        </div>
                        <div class="item-details">
                            <a href="{{ route('product.show', $item->product->id) }}">
                                <h3>{{ $item->product->title }}</h3>
                            </a>
                            <p class="item-size">Размер: {{ $item->size->name }}</p>
                            <div class="quantity-controls">
                                <button class="quantity-btn minus">-</button>
                                @php
                                    $key = $item->product->id . '_' . $item->size->id;
                                @endphp
                                <input
                                    type="number"
                                    name="products[{{ $item->product->id }}][quantity]"
                                    value="{{ $item->quantity }}"
                                    min="1"
                                    max="{{ $item->size->available_quantity }}"
                                    class="quantity-input"
                                    data-max="{{ $item->size->available_quantity }}"
                                    data-key="{{ $key }}"
                                    readonly
                                >

                                <button class="quantity-btn plus">+</button>
                            </div>
                        </div>
                        <div class="item-price">
                            <p class="price">{{ $item->product->formattedPrice }}</p>
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                <input type="hidden" name="size_id" value="{{ $item->size->id }}">
                                <button type="submit" class="remove-item">Удалить</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
                <div class="basket-summary">
                    <h2>Итого</h2>
                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Товары ({{ $cartCount }})</span>
                            <span>{{ $formattedTotalPrice }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Доставка</span>
                            <span>Бесплатно</span>
                        </div>
                        <div class="summary-row total">
                            <span>Итого к оплате</span>
                            <span>{{ $formattedTotalPrice }}</span>
                        </div>
                    </div>
                    <form action="{{ route('order.store') }}" method="POST" id="checkout-form">
                        @csrf
                        <input type="hidden" name="total_price" id="total_price_input" value="{{ $formattedTotalPrice }}">
                        @foreach($items as $item)
                            @php
                                $key = $item->product->id . '_' . $item->size->id;
                            @endphp
                            <input type="hidden" name="products[{{ $key }}][id]" value="{{ $item->product->id }}">
                            <input type="hidden" name="products[{{ $key }}][size_id]" value="{{ $item->size->id ?? null }}">
                            <input type="hidden" name="products[{{ $key }}][quantity]" value="{{ $item->quantity }}" class="hidden-quantity">
                        @endforeach
                        <button type="submit" class="checkout-btn">Оформить заказ</button>
                    </form>
                    <div class="promo-code">
                        <input type="text" placeholder="Введите промокод">
                        <button>Применить</button>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="empty-basket">
            <h2>Ваша корзина пуста</h2>
            <p>Добавьте товары в корзину, чтобы сделать заказ</p>
            <a href="{{ route('catalog.index') }}" class="continue-shopping">Перейти к покупкам</a>
        </div>
        @endif
        <div class="empty-basket" style="display: none">
            <h2>Ваша корзина пуста</h2>
            <p>Добавьте товары в корзину, чтобы сделать заказ</p>
            <a href="{{ route('catalog.index') }}" class="continue-shopping">Перейти к покупкам</a>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quantityControls = document.querySelectorAll('.quantity-controls');
            const removeButtons = document.querySelectorAll('.remove-item');
            const emptyBasket = document.querySelector('.empty-basket');
            const basketContent = document.querySelector('.basket-content');

            quantityControls.forEach(control => {
                const minusBtn = control.querySelector('.minus');
                const plusBtn = control.querySelector('.plus');
                const input = control.querySelector('.quantity-input');
                const key = input.getAttribute('data-key');
                const hiddenQuantityInput = document.querySelector(`input[name="products[${key}][quantity]"].hidden-quantity`);

                minusBtn.addEventListener('click', () => {
                    let value = parseInt(input.value);
                    if (value > 1) {
                        input.value = value - 1;
                        updateTotalPrice();
                        updateHiddenQuantity(input, hiddenQuantityInput); // Обновление скрытого инпута
                    }
                });

                plusBtn.addEventListener('click', () => {
                    let value = parseInt(input.value);
                    const max = parseInt(input.dataset.max);
                    if (value < max) {
                        input.value = value + 1;
                        updateTotalPrice();
                        updateHiddenQuantity(input, hiddenQuantityInput); // Обновление скрытого инпута
                    }
                });

                input.addEventListener('change', () => {
                    let value = parseInt(input.value);
                    const max = parseInt(input.dataset.max);
                    if (value < 1) input.value = 1;
                    if (value > max) input.value = max;
                    updateHiddenQuantity(input, hiddenQuantityInput); // Обновление скрытого инпута
                });
            });

            function updateTotalPrice() {
                let total = 0;
                const items = document.querySelectorAll('.basket-item');

                items.forEach(item => {
                    const price = parseInt(item.querySelector('.price').textContent.replace(/[^\d]/g, ''));
                    const quantity = parseInt(item.querySelector('.quantity-input').value);
                    total += price * quantity;
                });

                // Обновляем отображение суммы
                document.querySelector('.summary-row:first-child span:last-child').textContent = total.toLocaleString() + '₸';
                document.querySelector('.summary-row.total span:last-child').textContent = total.toLocaleString() + '₸';
                document.querySelector('.summary-row:first-child span:first-child').textContent = `Товары (${items.length})`;

                // Обновляем значение поля input с id total_price_input
                const totalInput = document.getElementById('total_price_input');
                if (totalInput) {
                    totalInput.value = total.toLocaleString(); // Обновляем значение поля total_price_input
                }
            }

            function updateHiddenQuantity(input, hiddenQuantityInput) {
                hiddenQuantityInput.value = input.value; // Обновляем значение скрытого инпута
            }

            // Проверка на пустую корзину
            function checkEmptyBasket() {
                const items = document.querySelectorAll('.basket-item');
                if (items.length === 0) {
                    basketContent.style.display = 'none';
                    emptyBasket.style.display = 'block';
                }
            }

            // Обработка промокода
            const promoForm = document.querySelector('.promo-code');
            const promoInput = promoForm.querySelector('input');
            const promoButton = promoForm.querySelector('button');

            promoButton.addEventListener('click', () => {
                const promoCode = promoInput.value.trim().toUpperCase();
                if (promoCode === 'NAMYS2025') {
                    alert('Промокод применен! Скидка 10%');
                    applyDiscount(10);
                } else {
                    alert('Неверный промокод');
                }
            });

            function applyDiscount(percentage) {
                const total = parseInt(document.querySelector('.summary-row.total span:last-child').textContent.replace(/[^\d]/g, ''));
                const discountAmount = total * (percentage / 100);
                const newTotal = total - discountAmount;

                const summaryDetails = document.querySelector('.summary-details');
                const discountRow = document.createElement('div');
                discountRow.className = 'summary-row';
                discountRow.innerHTML = `
            <span>Скидка ${percentage}%</span>
            <span>-${discountAmount.toLocaleString()}₸</span>
        `;

                const totalRow = document.querySelector('.summary-row.total');
                summaryDetails.insertBefore(discountRow, totalRow);

                totalRow.querySelector('span:last-child').textContent = newTotal.toLocaleString() + '₸';

                promoInput.disabled = true;
                promoButton.disabled = true;
            }

            // Обработка оформления заказа
            const checkoutForm = document.getElementById('checkout-form');
            const totalInput = document.getElementById('total_price_input');

            if (checkoutForm && totalInput) {
                checkoutForm.addEventListener('submit', function () {
                    const totalText = document.querySelector('.summary-row.total span:last-child').textContent;
                    const numericTotal = parseInt(totalText.replace(/[^\d]/g, '')) || 0;

                    totalInput.value = numericTotal;
                });
            }

            // Обработчик для удаления товара из корзины
            removeButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = button.closest('form');
                    const item = button.closest('.basket-item');

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form)
                    })
                        .then(res => {
                            if (res.ok) {
                                if (item) {
                                    item.remove();
                                } else {
                                    console.warn('Не найден .basket-item для удаления');
                                }
                                updateTotalPrice();
                                checkEmptyBasket();
                            } else {
                                alert('Ошибка при удалении');
                            }
                        });
                });
            });

            checkEmptyBasket();
        });
    </script>
@endsection
