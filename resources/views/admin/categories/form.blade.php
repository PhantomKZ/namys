@extends('adminlte::page')

@section('title', 'Категории')

@section('content')
    <div class="container-fluid">
        <h1>{{ isset($category) ? 'Редактировать' : 'Создать' }} категорию</h1>

        <form action="{{ isset($category) ? route('admin.categories.update', $category->slug) : route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($category))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Название категории</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ isset($category) ? $category->name : old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="thumbnail">Обложка категории</label>
                <input type="file" id="thumbnail" name="thumbnail" class="form-control">
                @if (isset($category) && $category->thumbnail)
                    <img src="{{ asset($category->thumbnail) }}" alt="Thumbnail" class="mt-2" style="width: 100px;">
                @endif
            </div>

            <div class="form-group">
                <label for="types">Типы</label>
                <select name="type_ids[]" id="type-select" multiple class="form-control">
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}"
                            {{ isset($category) && $category->types->contains($type->id) ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn {{ isset($category) ? 'btn-warning' : 'btn-success' }} mt-3">
                {{ isset($category) ? 'Обновить' : 'Создать' }}
            </button>
        </form>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#type-select').select2({
                placeholder: "Выберите типы",
                allowClear: true
            });
        });
    </script>
@endsection
