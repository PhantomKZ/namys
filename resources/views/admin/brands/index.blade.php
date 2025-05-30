@extends('adminlte::page')

@section('title', 'Бренды')

@section('content_header')
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>Бренды</h1>
            <button type="button" class="btn btn-primary" id="openCreateModal">
                <i class="fas fa-plus"></i> Добавить бренд
            </button>
            <!-- Модальное окно -->
            <div class="modal fade" id="brandModal" tabindex="-1" role="dialog" aria-labelledby="brandModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="brandForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="brandModalLabel">Добавить бренд</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="brandName">Название</label>
                                    <input type="text" class="form-control" id="brandName" name="name" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="brandModalSubmit">Сохранить</button>
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
            @foreach ($brands as $brand)
                <tr>
                    <td>{{ $brand->name }}</td>
                    <td>
                        <button type="button"
                                class="btn btn-warning editBrandBtn"
                                data-id="{{ $brand->id }}"
                                data-name="{{ $brand->name }}">
                            Редактировать
                        </button>
                        <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" style="display: inline;">
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
            const modal = new bootstrap.Modal(document.getElementById('brandModal'));
            const brandModalSubmit = document.getElementById('brandModalSubmit');

            document.getElementById('openCreateModal').addEventListener('click', function () {
                document.getElementById('brandModalLabel').textContent = 'Добавить бренд';
                document.getElementById('brandName').value = '';
                document.getElementById('brandForm').action = "{{ route('admin.brands.store') }}";
                document.getElementById('formMethod').value = 'POST';
                brandModalSubmit.textContent = 'Добавить';
                modal.show();
            });

            document.querySelectorAll('.editBrandBtn').forEach(button => {
                button.addEventListener('click', function () {
                    const brandId = this.dataset.id;
                    const brandName = this.dataset.name;

                    document.getElementById('brandModalLabel').textContent = 'Редактировать бренд';
                    document.getElementById('brandName').value = brandName;
                    document.getElementById('brandForm').action = `/admin/brands/${brandId}`;
                    document.getElementById('formMethod').value = 'PUT';
                    brandModalSubmit.textContent = 'Сохранить';
                    modal.show();
                });
            });
        });
    </script>
@endsection
