@extends('adminlte::page')

@section('title', 'Цвета одежды')

@section('content_header')
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>Цвета одежды</h1>
            <button type="button" class="btn btn-primary" id="openCreateColorModal">
                <i class="fas fa-plus"></i> Добавить цвет
            </button>
            <!-- Модальное окно для создания/редактирования цвета -->
            <div class="modal fade" id="colorModal" tabindex="-1" role="dialog" aria-labelledby="colorModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="colorForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="colorModalLabel">Добавить цвет</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="colorName">Название</label>
                                    <input type="text" class="form-control" id="colorName" name="name" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="colorModalSubmit">Сохранить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
                        <button type="button"
                                class="btn btn-warning editColorBtn"
                                data-id="{{ $color->id }}"
                                data-name="{{ $color->name }}">
                            Редактировать
                        </button>
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
@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modal = new bootstrap.Modal(document.getElementById('colorModal'));
            const colorModalSubmit = document.getElementById('colorModalSubmit');

            // Открытие модалки для добавления цвета
            document.getElementById('openCreateColorModal').addEventListener('click', function () {
                document.getElementById('colorModalLabel').textContent = 'Добавить цвет';
                document.getElementById('colorName').value = '';
                document.getElementById('colorForm').action = "{{ route('admin.colors.store') }}";
                document.getElementById('formMethod').value = 'POST';
                colorModalSubmit.textContent = 'Добавить';
                modal.show();
            });

            // Открытие модалки для редактирования цвета
            document.querySelectorAll('.editColorBtn').forEach(button => {
                button.addEventListener('click', function () {
                    const colorId = this.dataset.id;
                    const colorName = this.dataset.name;

                    document.getElementById('colorModalLabel').textContent = 'Редактировать цвет';
                    document.getElementById('colorName').value = colorName;
                    document.getElementById('colorForm').action = `/admin/colors/${colorId}`;
                    document.getElementById('formMethod').value = 'PUT';
                    colorModalSubmit.textContent = 'Сохранить';
                    modal.show();
                });
            });
        });
    </script>
@endsection
