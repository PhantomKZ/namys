@extends('adminlte::page')

@section('title', 'Материалы одежды')

@section('content_header')
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>Материалы одежды</h1>
            <button type="button" class="btn btn-primary" id="openCreateMaterialModal">
                <i class="fas fa-plus"></i> Добавить материал
            </button>
            <!-- Модальное окно для создания/редактирования материала -->
            <div class="modal fade" id="materialModal" tabindex="-1" role="dialog" aria-labelledby="materialModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="materialForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="materialModalLabel">Добавить материал</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="materialName">Название</label>
                                    <input type="text" class="form-control" id="materialName" name="name" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="materialModalSubmit">Сохранить</button>
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
            @foreach ($materials as $material)
                <tr>
                    <td>{{ $material->name }}</td>
                    <td>
                        <button type="button"
                                class="btn btn-warning editMaterialBtn"
                                data-id="{{ $material->id }}"
                                data-name="{{ $material->name }}">
                            Редактировать
                        </button>
                        <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST" style="display: inline;">
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
            const modal = new bootstrap.Modal(document.getElementById('materialModal'));
            const materialModalSubmit = document.getElementById('materialModalSubmit');

            // Открытие модалки для добавления материала
            document.getElementById('openCreateMaterialModal').addEventListener('click', function () {
                document.getElementById('materialModalLabel').textContent = 'Добавить материал';
                document.getElementById('materialName').value = '';
                document.getElementById('materialForm').action = "{{ route('admin.materials.store') }}";
                document.getElementById('formMethod').value = 'POST';
                materialModalSubmit.textContent = 'Добавить';
                modal.show();
            });

            // Открытие модалки для редактирования материала
            document.querySelectorAll('.editMaterialBtn').forEach(button => {
                button.addEventListener('click', function () {
                    const materialId = this.dataset.id;
                    const materialName = this.dataset.name;

                    document.getElementById('materialModalLabel').textContent = 'Редактировать материал';
                    document.getElementById('materialName').value = materialName;
                    document.getElementById('materialForm').action = `/admin/materials/${materialId}`;
                    document.getElementById('formMethod').value = 'PUT';
                    materialModalSubmit.textContent = 'Сохранить';
                    modal.show();
                });
            });
        });
    </script>
@endsection
