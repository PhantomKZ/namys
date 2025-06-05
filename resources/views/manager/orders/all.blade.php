@extends('layouts.app')

@section('title', 'Все заказы')

@section('content')
<div class="container">
    <h1>Все заказы</h1>

    @if ($orders->isEmpty())
        <p>Нет заказов.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Номер заказа</th>
                    <th>Клиент</th>
                    <th>Дата заказа</th>
                    <th>Статус</th>
                    <th>Действия</th>
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
                            {{-- Возможно, добавить ссылку на просмотр деталей заказа, даже если он обработан --}}
                            <a href="{{ route('manager.orders.process', $order) }}" class="btn btn-info btn-sm">Посмотреть</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection 