@extends('adminlte::page')

@section('title', 'Управление менеджерами')

@section('content_header')
    <h1>Управление менеджерами</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Список пользователей</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->getRoleNames() as $roleName)
                                    <span class="badge badge-info">{{ $roleName }}</span>
                                @endforeach
                            </td>
                            <td>
                                <form action="{{ route('admin.managers.updateRole', $user) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <select name="role" class="form-control">
                                            <option value="client" {{ $user->hasRole('client') ? 'selected' : '' }}>Клиент</option>
                                            <option value="manager" {{ $user->hasRole('manager') ? 'selected' : '' }}>Менеджер</option>
                                            <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Админ</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Обновить роль</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop 