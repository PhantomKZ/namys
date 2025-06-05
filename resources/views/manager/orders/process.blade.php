@extends('layouts.app')

@section('title', 'Обработка заказа №{{ $order->id }}')

@section('content')
<div class="container">
    <h1>Обработка заказа №{{ $order->id }}</h1>

    <div class="card mb-3">
        <div class="card-header">
            Информация о заказе
        </div>
        <div class="card-body">
            <p><strong>Статус:</strong> {{ $order->status }}</p>
            <p><strong>Дата заказа:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
            <p><strong>Общая сумма:</strong> {{ $order->formattedPrice }}</p>

            <h5>Товары в заказе:</h5>
            <ul>
                @foreach($order->products as $product)
                    <li>{{ $product->title }} - {{ $product->pivot->quantity }} шт.</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            Информация о клиенте
        </div>
        <div class="card-body">
            {{-- Информация о клиенте --}}
            <p><strong>Клиент:</strong> {{ $order->user->name ?? 'Удален' }}</p>
            <p><strong>Email:</strong> {{ $order->user->email ?? 'Не указан' }}</p>

            {{-- Поля ввода для данных клиента и комментария менеджера --}}
            <div class="form-group">
                <label for="client_phone">Номер телефона клиента:</label>
                <input type="text" name="client_phone" id="client_phone" class="form-control" value="{{ $order->client_phone ?? ($order->user->phone ?? '') }}"> {{-- Предполагается, что у пользователя есть поле phone --}}
            </div>

            <div class="form-group">
                <label for="shipping_address">Адрес доставки:</label>
                <textarea name="shipping_address" id="shipping_address" class="form-control">{{ $order->shipping_address ?? '' }}</textarea> {{-- Предполагается, что у заказа есть поле shipping_address --}}
            </div>

            {{-- Поле для комментария менеджера --}}
            <div class="form-group">
                <label for="manager_comment">Комментарий менеджера:</label>
                <textarea name="manager_comment" id="manager_comment" class="form-control">{{ $order->manager_comment ?? '' }}</textarea>
            </div>

        </div>
    </div>

    {{-- Кнопки Сохранить и Завершить обработку --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form action="{{ route('manager.orders.save', $order) }}" method="POST" id="saveOrderForm">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
        <form action="{{ route('manager.orders.complete', $order) }}" method="POST" id="completeOrderForm">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-success">Завершить обработку</button>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const saveForm = document.getElementById('saveOrderForm');
        const completeForm = document.getElementById('completeOrderForm');
        const clientPhoneInput = document.getElementById('client_phone');
        const shippingAddressTextarea = document.getElementById('shipping_address');
        const managerCommentTextarea = document.getElementById('manager_comment');

        function addHiddenFields(form) {
            // Удаляем старые скрытые поля, если они есть (на всякий случай)
            form.querySelectorAll('input[type="hidden"][name="client_phone"], input[type="hidden"][name="shipping_address"], input[type="hidden"][name="manager_comment"]').forEach(input => input.remove());

            // Добавляем текущие значения полей как скрытые поля
            const phoneInput = document.createElement('input');
            phoneInput.type = 'hidden';
            phoneInput.name = 'client_phone';
            phoneInput.value = clientPhoneInput.value;
            form.appendChild(phoneInput);

            const addressInput = document.createElement('input');
            addressInput.type = 'hidden';
            addressInput.name = 'shipping_address';
            addressInput.value = shippingAddressTextarea.value;
            form.appendChild(addressInput);

            const commentInput = document.createElement('input');
            commentInput.type = 'hidden';
            commentInput.name = 'manager_comment';
            commentInput.value = managerCommentTextarea.value;
            form.appendChild(commentInput);
        }

        saveForm.addEventListener('submit', function (e) {
            // Перед отправкой формы Сохранить, добавляем актуальные данные полей
            addHiddenFields(saveForm);
        });

        completeForm.addEventListener('submit', function (e) {
            // Перед отправкой формы Завершить обработку, добавляем актуальные данные полей
            addHiddenFields(completeForm);
        });
    });
</script>
@endsection 