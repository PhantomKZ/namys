@extends('adminlte::page')

@section('title', isset($product) ? 'Редактировать товар' : 'Создать товар')

@section('content')
    <div class="container-fluid">
        <h1>{{ isset($product) ? 'Редактировать товар' : 'Создать товар' }}</h1>
        <form
            action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" name="name" id="name" class="form-control"
                       value="{{ old('name', $product->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="brand_id">Бренд</label>
                <select name="brand_id" id="brand_id" class="form-control" required>
                    <option value="" selected> Выберите бренд </option>
                    @foreach($brands as $brand)
                        <option
                            value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="type_id">Тип одежды</label>
                <select name="type_id" id="type_id" class="form-control" required>
                    <option value="" selected> Выберите тип </option>
                    @foreach($types as $type)
                        <option
                            value="{{ $type->id }}" {{ old('type_id', $product->type_id ?? '') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="materials">Материал(ы)</label>

                <select name="material_ids[]"
                        id="materials"
                        class="form-control select2"
                        multiple
                        required>
                    @foreach($materials as $material)
                        <option value="{{ $material->id }}"
                            {{ in_array(
                                    $material->id,
                                    old(
                                        'material_ids',
                                        isset($product)
                                            ? $product->materials->pluck('id')->toArray()
                                            : []
                                    )
                                ) ? 'selected' : '' }}>
                            {{ $material->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="color_id">Цвет</label>
                <select name="color_id" id="color_id" class="form-control" required>
                    <option value="" selected> Выберите цвет </option>
                    @foreach($colors as $color)
                        <option
                            value="{{ $color->id }}" {{ old('color_id', $product->color_id ?? '') == $color->id ? 'selected' : '' }}>
                            {{ $color->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="price">Цена</label>
                <input type="number" name="price" id="price" class="form-control"
                       value="{{ old('price', $product->price ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="description">Описание</label>
                <textarea name="description" id="description" rows="3"
                          class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <h4 class="mt-4">Размеры и количество</h4>
            @foreach($sizes as $size)
                <div class="form-group">
                    <div class="form-check">
                        <div class="d-flex align-items-center mb-2">
                            <label class="form-check-label me-2" for="size_{{ $size->id }}" style="min-width: 40px;">
                                {{ $size->name }}
                            </label>
                            <input type="number"
                                   id="size_{{ $size->id }}"
                                   name="quantities[{{ $size->id }}]"
                                   class="form-control mt-1"
                                   placeholder="Количество для {{ $size->name }}"
                                   value="{{ isset($product) ? $product->sizes->find($size->id)?->pivot->quantity : 0 }}">
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="form-check mt-3">
                <input type="checkbox" name="is_limited" id="is_limited" class="form-check-input"
                    {{ old('is_limited', $product->is_limited ?? false) ? 'checked' : '' }}>
                <label for="is_limited" class="form-check-label">Limited edition</label>
            </div>

            <hr class="my-4">

            <h4>Изображения</h4>

            <div id="image-upload-wrapper">
                @isset($product)
                    @foreach($product->images as $index => $image)
                        <div class="image-upload-group border rounded p-3 mb-2 position-relative">
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

                            <div class="form-check">
                                <input type="checkbox" name="hover_image_index" value="{{ $index }}" class="form-check-input hover-checkbox"
                                       {{ $image->is_hover ? 'checked' : '' }} id="hover_{{ $index }}">
                                <label for="hover_{{ $index }}" class="form-check-label">Hover изображение</label>
                            </div>
                            <button type="button" class="btn btn-danger m-2 remove-image" aria-label="Удалить"> Удалить </button>
                        </div>
                    @endforeach
                @endisset

                <div class="image-upload-group border rounded p-3 mb-2 position-relative">

                    <div class="form-group">
                        <label>Новое изображение</label>
                        <input type="file" name="images[]" class="form-control">
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="main_image_index" value="0" class="form-check-input main-checkbox" id="main_0">
                        <label for="main_0" class="form-check-label">Главное изображение</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="hover_image_index" value="0" class="form-check-input hover-checkbox" id="hover_0">
                        <label for="hover_0" class="form-check-label">Hover изображение</label>
                    </div>
                    <button type="button" class="btn btn-danger m-2 remove-image" aria-label="Удалить"> Удалить </button>
                </div>
            </div>

            <button type="button" id="add-image" class="btn btn-outline-primary mb-3">
                <i class="fas fa-plus"></i> Добавить изображение
            </button>

            <div>
                <button type="submit" class="btn btn-success mt-3 mb-2">
                    {{ isset($product) ? 'Сохранить изменения' : 'Создать товар' }}
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

            <div class="form-check">
                <input type="checkbox" name="hover_image_index" value="${imageIndex}" class="form-check-input hover-checkbox" id="hover_${imageIndex}">
                <label for="hover_${imageIndex}" class="form-check-label">Hover изображение</label>
            </div>
            <button type="button" class="btn btn-danger m-2 remove-image" aria-label="Удалить"> Удалить </button>
        </div>
        `;

            wrapper.insertAdjacentHTML('beforeend', html);
            imageIndex++;
        });

        // Делегирование для чекбоксов (только один может быть выбран из группы)
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('main-checkbox')) {
                document.querySelectorAll('.main-checkbox').forEach(checkbox => {
                    if (checkbox !== e.target) checkbox.checked = false;
                });
            }

            if (e.target.classList.contains('hover-checkbox')) {
                document.querySelectorAll('.hover-checkbox').forEach(checkbox => {
                    if (checkbox !== e.target) checkbox.checked = false;
                });
            }
        });

        // Делегирование для кнопки удаления
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-image')) {
                e.target.closest('.image-upload-group').remove();
            }
        });
    </script>

    <script>
        $(function () {
            $('#materials').select2({
                placeholder: 'Выберите материал(ы)',
                width: '100%',
                language: 'ru'            // локализация (опционально)
            });
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
@endsection
