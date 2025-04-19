@extends('adminlte::page')

@section('title', 'Товары')

@section('content_header')
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>Товары</h1>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Добавить товар</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered mt-4">
            <thead>
            <tr>
                <th>№</th> <!-- Добавляем заголовок для нумерации -->
                <th>Название</th>
                <th>Тип</th>
                <th>Бренд</th>
                <th>Материал</th>
                <th>Цвет</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody id="sortable">
            @foreach ($products as $index => $product)
                <tr id="product-{{ $product->id }}" data-id="{{ $product->id }}">
                    <td>{{ $products->firstItem() + $index }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->type }}</td>
                    <td>{{ $product->brand }}</td>
                    <td>{{ $product->material }}</td>
                    <td>{{ $product->color }}</td>
                    <td>{{ $product->price }} ₸</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm">Редактировать</a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Удалить товар?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $products->links('pagination::bootstrap-4') }}
    </div>
@endsection

@section('js')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script>
        $(document).ready(function() {
            $("#sortable").sortable({
                update: function(event, ui) {
                    var order = $(this).sortable('toArray', { attribute: 'data-id' });

                    $.ajax({
                        url: "{{ route('admin.products.updateOrder') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            order: order
                        },
                    });
                }
            });
        });
    </script>

@endsection
