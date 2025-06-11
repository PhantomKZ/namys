@extends('layouts.app')

@section('title', __('Все заказы'))

@section('content')
<div class="container">
    <h1>{{ __('Все заказы') }}</h1>

    @if ($orders->isEmpty())
        <p>{{ __('Нет заказов.') }}</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('Номер заказа') }}</th>
                    <th>{{ __('Клиент') }}</th>
                    <th>{{ __('Дата заказа') }}</th>
                    <th>{{ __('Статус') }}</th>
                    <th>{{ __('Действия') }}</th>
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
                            {{-- Возможно, добавить ссылку на просмотр деталей заказа, даже если он обработан --}}
                            <a href="{{ route('manager.orders.process', $order) }}" class="btn btn-info btn-sm">{{ __('Посмотреть') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection 