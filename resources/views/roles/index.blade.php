@extends('layout')

@section('content')
    <div class="container">
        <h2>Liste des groupes</h2>

        <a href="{{ route('roles.create') }}" class="btn btn-success m-1">Ajouter</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    <tr>
                        <td>{{ ucfirst($role->name) }}</td>
                        <td>
                            {{-- Add dropdown for edit and delete actions --}}
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="actionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="actionsDropdown">
                                    <a class="dropdown-item" href="{{ route('roles.show', $role->id) }}">Détails</a>
                                    <form action="#" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Aucun role.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
