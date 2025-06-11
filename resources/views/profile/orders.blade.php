@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Мои заказы</h2>

        @if($orders->isEmpty())
            <p>У вас пока нет заказов.</p>
        @else
            <div class="accordion" id="ordersAccordion">
                @foreach($orders as $order)
                    <div class="card mb-3">
                        <div class="card-header p-0" id="heading{{ $order->id }}">
                            <button class="btn d-flex justify-content-between align-items-center w-100 collapsed order-toggle" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $order->id }}"
                                    aria-expanded="false"
                                    aria-controls="collapse{{ $order->id }}">
                                <div class="text-start p-3">
                                    <strong>Заказ №{{ $order->id }}</strong><br>
                                    <small>{{ __($order->status) }} — {{ $order->created_at->format('d.m.Y H:i') }}</small>
                                </div>
                                <div class="d-flex align-items-center pe-3">
                                    <span class="me-2">{{ $order->formattedPrice }}</span>
                                    <i class="arrow-icon bi bi-chevron-down"></i>
                                </div>
                            </button>
                        </div>

                        <div id="collapse{{ $order->id }}" class="collapse" aria-labelledby="heading{{ $order->id }}" data-bs-parent="#ordersAccordion">
                            <div class="card-body">
                                <ul class="list-group">
                                    @foreach($order->products as $product)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <a class="{{ route('product.show', $product->id) }}" href="{{ route('product.show', $product->id) }}">
                                                    <div class="d-flex">
                                                        <div class="me-2">
                                                            <img src="{{ asset($product->mainImage) }}" height="70px">
                                                        </div>
                                                        <div>
                                                            <strong>{{ $product->title }}</strong><br>
                                                            Размер: {{ $product->pivot->size_name ?? '—' }}<br>
                                                            Кол-во: {{ $product->pivot->quantity }}
                                                        </div>
                                                    </div>
                                                </a>
                                            <span>{{ number_format($product->pivot->total_price, 0, '.', ' ') }} ₸</span>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
