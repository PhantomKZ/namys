@extends('adminlte::page')

@section('title', 'Коллекции одежды')

@section('content_header')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-2">
            <h1>Коллекции одежды</h1>
            <a href="{{ route('admin.collections.create') }}" type="button" class="btn btn-primary">
                <i class="fas fa-plus"></i> Добавить коллекцию
            </a>
        </div>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Описание</th>
                <th>Кол-во товаров</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @forelse($collections as $collection)
                <tr>
                    <td>{{ $collection->id }}</td>
                    <td>{{ $collection->name }}</td>
                    <td>{{ $collection->description }}</td>
                    <td>{{ $collection->products->count() }}</td>
                    <td>
                        <a href="{{ route('admin.collections.edit', $collection) }}" class="btn btn-sm btn-primary">Редактировать</a>

                        <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Удалить коллекцию?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Коллекции не найдены</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
