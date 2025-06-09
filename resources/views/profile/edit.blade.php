@extends('layouts.app')
@section('content')
    <div class="container" style="max-width: 500px">
        <form method="POST" action="{{route('profile.update')}}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-center flex-column align-items-center mb-3">
                <div class="mb-3 w-100">
                    <label for="name" class="form-label">{{ __('messages.name') }}</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('messages.enter_name') }}"
                           value="{{ old('name', auth()->user()->name ?? '') }}">
                </div>
                <div class="mb-3 w-100">
                    <label for="imageInput" class="form-label">{{ __('messages.avatar') }}</label>
                    <input type="file" name="avatar" id="imageInput" accept="image/jpeg,image/png,image/jpg,image/gif,image/tiff" class="form-control mb-2" style="max-width: 300px;">

                    <div class="image-preview-container" style="position: relative; width: 200px; height: 200px; margin: 0 auto; overflow: hidden; border-radius: 50%;">
                        <img id="preview"
                             src="{{ $user->avatar ? asset($user->avatar) : asset('images/avatar.png') }}"
                             alt="{{ __('messages.avatar') }}"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>

                <div class="mb-3 w-100">
                    <label for="phone" class="form-label">{{ __('messages.phone_number') }}</label>
                    <input  type="tel"
                            name="phone"
                            id="phone"
                            class="form-control"
                            placeholder="+7(XXX)XXX-XX-XX"
                            pattern="\+7\d{10}"
                            maxlength="12"
                            value="{{ old('phone', auth()->user()->phone ?? '') }}">
                    <div class="form-text">{{ __('messages.phone_format') }}</div>
                </div>

                <div class="mb-3 w-100">
                    <label for="current_password" class="form-label">{{ __('messages.current_password') }}</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="{{ __('messages.enter_current_password') }}">
                </div>
                <div class="mb-3 w-100">
                    <label for="new_password" class="form-label">{{ __('messages.new_password') }}</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="{{ __('messages.enter_new_password') }}">
                </div>
                <div class="mb-3 w-100">
                    <label for="new_password_confirmation" class="form-label">{{ __('messages.confirm_password') }}</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="{{ __('messages.repeat_new_password') }}">
                </div>

                <div class="text-center ms-auto">
                    <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
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

        document.getElementById('imageInput').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', () => {
                phoneInput.value = phoneInput.value.replace(/[^+\d]/g, '');
                if (!phoneInput.value.startsWith('+7')) {
                    phoneInput.value = '+7' + phoneInput.value.replace(/^(\+)?7?/, '');
                }
                phoneInput.value = phoneInput.value.slice(0, 12);
            });
        });
    </script>
@endsection
