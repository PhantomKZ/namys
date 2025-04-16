@extends('adminlte::page')

@section('title', 'Бренды')

@section('content')
    <div class="container-fluid">
        <h1>{{ isset($brand) ? 'Редактировать' : 'Создать' }} категорию</h1>

        <form action="{{ isset($brand) ? route('admin.brands.update', $brand->id) : route('admin.brands.store') }}" method="POST">
            @csrf
            @if (isset($brand))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ isset($brand) ? $brand->name : old('name') }}" required>
            </div>

            <button type="submit" class="btn {{ isset($brand) ? 'btn-warning' : 'btn-success' }} mt-3">
                {{ isset($brand) ? 'Обновить' : 'Создать' }}
            </button>
        </form>
    </div>
@stop
