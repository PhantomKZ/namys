@extends('adminlte::page')

@section('title', 'Типы одежды')

@section('content_header')
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>Типы одежды</h1>
            <button type="button" class="btn btn-primary" id="openCreateModal">
                <i class="fas fa-plus"></i> Добавить тип
            </button>
            <!-- Модальное окно для создания/редактирования типа -->
            <div class="modal fade" id="typeModal" tabindex="-1" role="dialog" aria-labelledby="typeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="typeForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="typeModalLabel">Добавить тип</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="typeName">Название</label>
                                    <input type="text" class="form-control" id="typeName" name="name" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="typeModalSubmit">Сохранить</button>
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

        @if(session('error'))
            <div class="alert alert-danger mt-4">
                {{ session('error') }}
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
            @foreach ($types as $type)
                <tr>
                    <td>{{ $type->name }}</td>
                    <td>
                        <button type="button"
                                class="btn btn-warning editTypeBtn"
                                data-id="{{ $type->id }}"
                                data-name="{{ $type->name }}">
                            Редактировать
                        </button>
                        <form action="{{ route('admin.types.destroy', $type->id) }}" method="POST" style="display: inline;">
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
            const modal = new bootstrap.Modal(document.getElementById('typeModal'));
            const typeModalSubmit = document.getElementById('typeModalSubmit');

            document.getElementById('openCreateModal').addEventListener('click', function () {
                document.getElementById('typeModalLabel').textContent = 'Добавить тип';
                document.getElementById('typeName').value = '';
                document.getElementById('typeForm').action = "{{ route('admin.types.store') }}";
                document.getElementById('formMethod').value = 'POST';
                typeModalSubmit.textContent = 'Добавить';
                modal.show();
            });

            document.querySelectorAll('.editTypeBtn').forEach(button => {
                button.addEventListener('click', function () {
                    const typeId = this.dataset.id;
                    const typeName = this.dataset.name;

                    document.getElementById('typeModalLabel').textContent = 'Редактировать тип';
                    document.getElementById('typeName').value = typeName;
                    document.getElementById('typeForm').action = `/admin/types/${typeId}`;
                    document.getElementById('formMethod').value = 'PUT';
                    typeModalSubmit.textContent = 'Сохранить';
                    modal.show();
                });
            });
        });
    </script>
@endsection

