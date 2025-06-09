@extends('layouts.app')

@section('title', __('Обработка заказов'))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ __('Обработка заказов') }}</h1>
        <a href="{{ route('manager.orders.all') }}" class="btn btn-secondary">{{ __('Все заказы') }}</a>
    </div>

    @if ($orders->isEmpty())
        <p>{{ __('Нет заказов для обработки.') }}</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('Номер заказа') }}</th>
                    <th>{{ __('Клиент') }}</th>
                    <th>{{ __('Дата заказа') }}</th>
                    <th>{{ __('Статус') }}</th>
                    <th>{{ __('Обработать') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? __('Удален') }}</td> {{-- Предполагается связь с пользователем --}}
                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td>{{ __($order->status) }}</td>
                        <td>
                            <a href="{{ route('manager.orders.process', $order) }}" class="btn btn-primary btn-sm">{{ __('Обработать') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection 