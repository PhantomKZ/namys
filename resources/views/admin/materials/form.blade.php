@extends('adminlte::page')

@section('title', 'Материалы')

@section('content')
    <div class="container-fluid">
        <h1>{{ isset($material) ? 'Редактировать' : 'Создать' }} материал</h1>

        <form action="{{ isset($material) ? route('admin.materials.update', $material->id) : route('admin.materials.store') }}" method="POST">
            @csrf
            @if (isset($material))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ isset($material) ? $material->name : old('name') }}" required>
            </div>

            <button type="submit" class="btn {{ isset($material) ? 'btn-warning' : 'btn-success' }} mt-3">
                {{ isset($material) ? 'Обновить' : 'Создать' }}
            </button>
        </form>
    </div>
@stop
