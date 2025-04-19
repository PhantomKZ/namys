@extends('adminlte::page')

@section('title', 'Коллекции одежды')

@section('content_header')
    <div class="container-fluid">
        <h1>{{ $collection->exists ? 'Редактировать коллекцию' : 'Создать коллекцию' }}</h1>

        <form action="{{ $collection->exists ? route('admin.collections.update', $collection) : route('admin.collections.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if($collection->exists)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Название коллекции</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $collection->name) }}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea name="description" class="form-control">{{ old('description', $collection->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="products" class="form-label">Товары</label>
                <select name="products[]" class="form-select" multiple>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            {{ isset($selected) && in_array($product->id, $selected) ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <h4>Изображения</h4>

            <div id="image-upload-wrapper">
                @isset($collection)
                    @foreach($collection->images as $index => $image)
                        <div class="image-upload-group border rounded p-3 mb-2">
                            <input type="hidden" name="existing_image_ids[]" value="{{ $image->id }}">
                            <div class="form-group">
                                <label>Изображение {{ $index + 1 }}</label>
                                <input type="file" name="images[]" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Текущее изображение:</label>
                                <img src="{{ asset($image->path) }}" alt="Image {{ $index + 1 }}" class="img-fluid mb-2">
                            </div>

                            <div class="form-check">
                                <input type="checkbox" name="main_image_index" value="{{ $index }}" class="form-check-input main-checkbox"
                                       {{ $image->is_main ? 'checked' : '' }} id="main_{{ $index }}">
                                <label for="main_{{ $index }}" class="form-check-label">Главное изображение</label>
                            </div>
                            <button type="button" class="btn btn-danger m-2 remove-image">Удалить</button>
                        </div>
                    @endforeach
                @endisset

                <div class="image-upload-group border rounded p-3 mb-2">

                    <div class="form-group">
                        <label>Новое изображение</label>
                        <input type="file" name="images[]" class="form-control">
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="main_image_index" value="0" class="form-check-input main-checkbox" id="main_0">
                        <label for="main_0" class="form-check-label">Главное изображение</label>
                    </div>
                    <button type="button" class="btn btn-danger m-2 remove-image">Удалить</button>
                </div>
            </div>

            <button type="button" id="add-image" class="btn btn-outline-primary mb-3">
                <i class="fas fa-plus"></i> Добавить изображение
            </button>
            <div>
                <button type="submit" class="btn btn-primary">
                    {{ $collection->exists ? 'Обновить' : 'Создать' }}
                </button>
            </div>
        </form>
    </div>
@endsection
@section('js')
    <script>
        let imageIndex = 1;

        document.getElementById('add-image').addEventListener('click', function () {
            const wrapper = document.getElementById('image-upload-wrapper');

            const html = `
            <div class="image-upload-group border rounded p-3 mb-2 position-relative">

                <div class="form-group">
                    <label>Изображение</label>
                    <input type="file" name="images[]" class="form-control" required>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="main_image_index" value="${imageIndex}" class="form-check-input main-checkbox" id="main_${imageIndex}">
                    <label for="main_${imageIndex}" class="form-check-label">Главное изображение</label>
                </div>
                <button type="button" class="btn btn-danger m-2 remove-image">Удалить</button>
            </div>
        `;
            wrapper.insertAdjacentHTML('beforeend', html);
            imageIndex++;
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('main-checkbox')) {
                document.querySelectorAll('.main-checkbox').forEach(checkbox => {
                    if (checkbox !== e.target) checkbox.checked = false;
                });
            }
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-image')) {
                e.target.closest('.image-upload-group').remove();
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');

            form.addEventListener('submit', function (e) {
                const mainImageChecked = document.querySelector('input[name="main_image_index"]:checked');

                if (!mainImageChecked) {
                    e.preventDefault();
                    alert('Пожалуйста, выберите главное изображение.');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('select.form-select').select2({
                placeholder: 'Выберите товары',
                width: '100%',
                allowClear: true
            });
        });
    </script>

@endsection
