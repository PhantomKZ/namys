@extends('layouts.app')

@section('title', 'Заказы в обработке')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Заказы в обработке</h1>
        <a href="{{ route('manager.orders.all') }}" class="btn btn-secondary">Все заказы</a>
    </div>

    @if ($orders->isEmpty())
        <p>Нет заказов для обработки.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Номер заказа</th>
                    <th>Клиент</th>
                    <th>Дата заказа</th>
                    <th>Статус</th>
                    <th>Обработать</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'Удален' }}</td> {{-- Предполагается связь с пользователем --}}
                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td>{{ $order->status }}</td>
                        <td>
                            <a href="{{ route('manager.orders.process', $order) }}" class="btn btn-primary btn-sm">Обработать</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection 