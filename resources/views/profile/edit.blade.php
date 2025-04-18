@extends('layouts.app')
@section('content')
    <div class="container" style="max-width: 500px">
        <form method="POST" action="{{route('profile.update')}}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-center flex-column align-items-center mb-3">
                <div class="mb-3 w-100">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Введите имя"
                           value="{{ old('name', auth()->user()->name ?? '') }}">
                </div>
                <div class="mb-3 w-100">
                    <label for="imageInput" class="form-label">Аватар</label>
                    <input type="file" name="avatar" id="imageInput" accept="image/*" class="form-control mb-2" style="max-width: 300px;">

                    <img id="preview"
                         src="{{ $user->avatar ? asset($user->avatar) : asset('images/avatar.png') }}"
                         alt="Аватар"
                         class="mx-auto d-block"
                         style="height: 200px; width: 200px; border-radius: 50%; margin: 20px; object-fit: cover;">
                </div>
                <div class="text-center ms-auto">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>

        </form>
    </div>
@endsection
@section('scripts')
    <script>
        window.addEventListener('load', function () {
            const imageInput = document.getElementById('imageInput');

            if (imageInput) {
                imageInput.value = '';
            }

        });
    </script>
    <script>
        document.getElementById('imageInput').addEventListener('change', function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
