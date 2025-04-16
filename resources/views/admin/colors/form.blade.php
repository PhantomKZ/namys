@extends('adminlte::page')

@section('title', 'Цвета одежды')

@section('content')
    <div class="container-fluid">
        <h1>{{ isset($color) ? 'Редактировать' : 'Создать' }} цвет</h1>

        <form action="{{ isset($color) ? route('admin.colors.update', $color->id) : route('admin.colors.store') }}" method="POST">
            @csrf
            @if (isset($color))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ isset($color) ? $color->name : old('name') }}" required>
            </div>

            <button type="submit" class="btn {{ isset($color) ? 'btn-warning' : 'btn-success' }} mt-3">
                {{ isset($color) ? 'Обновить' : 'Создать' }}
            </button>
        </form>
    </div>
@stop
