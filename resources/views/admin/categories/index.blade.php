@extends('adminlte::page')

@section('title', 'Категории')

@section('content_header')
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>Категории</h1>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Создать категорию</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="table mt-4">
            <thead>
            <tr>
                <th>Название</th>
                <th>Обложка</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>
                        @if ($category->thumbnail)
                            <img src="{{ asset('storage/' . $category->thumbnail) }}" alt="Thumbnail" style="width: 100px; height: auto;">
                        @else
                            <span>Без обложки</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category->slug) }}" class="btn btn-warning">Редактировать</a>
                        <form action="{{ route('admin.categories.destroy', $category->slug) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
