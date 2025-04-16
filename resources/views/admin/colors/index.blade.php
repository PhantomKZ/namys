@extends('adminlte::page')

@section('title', 'Цвета одежды')

@section('content_header')
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>Цвета одежды</h1>
            <a href="{{ route('admin.colors.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Добавить цвет </a>
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
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($colors as $color)
                <tr>
                    <td>{{ $color->name }}</td>
                    <td>
                        <a href="{{ route('admin.colors.edit', $color->id) }}" class="btn btn-warning">Редактировать</a>
                        <form action="{{ route('admin.colors.destroy', $color->id) }}" method="POST" style="display: inline;">
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
