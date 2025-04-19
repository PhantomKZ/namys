@extends('adminlte::page')

@section('title', 'Товары')

@section('content_header')
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>Товары</h1>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Добавить товар</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered mt-4">
            <thead>
            <tr>
                <th>Название</th>
                <th>Тип</th>
                <th>Бренд</th>
                <th>Материал</th>
                <th>Цвет</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->type }}</td>
                    <td>{{ $product->brand }}</td>
                    <td>{{ $product->material }}</td>
                    <td>{{ $product->color }}</td>
                    <td>{{ $product->price }} ₸</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm">Редактировать</a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Удалить товар?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $products->links() }}
    </div>
@endsection
