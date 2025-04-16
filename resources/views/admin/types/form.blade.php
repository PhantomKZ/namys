@extends('adminlte::page')

@section('title', 'Бренды')

@section('content')
    <div class="container-fluid">
        <h1>{{ isset($type) ? 'Редактировать' : 'Создать' }} тип</h1>

        <form action="{{ isset($type) ? route('admin.types.update', $type->id) : route('admin.types.store') }}" method="POST">
            @csrf
            @if (isset($type))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ isset($type) ? $type->name : old('name') }}" required>
            </div>

            <button type="submit" class="btn {{ isset($type) ? 'btn-warning' : 'btn-success' }} mt-3">
                {{ isset($type) ? 'Обновить' : 'Создать' }}
            </button>
        </form>
    </div>
@stop
