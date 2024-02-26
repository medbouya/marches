@extends('layout')

@section('content')
<div class="container">
    <h2>Liste des utilisateurs</h2>
    <a href="{{ route('users.register') }}" class="btn btn-success m-1">Ajouter</a>

    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Groupes</th>
                <th>Groupe assigné</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                <td>
                    <form action="{{ route('users.assignRole', $user) }}" method="POST">
                        @csrf
                        <select name="role" class="form-control">
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm mt-2">
                            Assigner ce groupe
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
