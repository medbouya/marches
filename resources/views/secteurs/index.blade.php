@extends('layout')

@section('content')
    <div class="container">
        <h2>Secteurs</h2>
        <a href="{{ route('secteurs.create') }}" class="btn btn-primary mb-2">Nouveau secteur</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($secteurs as $secteur)
                    <tr>
                        <td>{{ $secteur->id }}</td>
                        <td>{{ $secteur->name }}</td>
                        <td>{{ $secteur->description }}</td>
                        <td>
                            <a href="{{ route('secteurs.show', $secteur->id) }}" class="btn btn-info btn-sm">Voir</a>
                            <a href="{{ route('secteurs.edit', $secteur->id) }}" class="btn btn-primary btn-sm">Editer</a>
                            <form action="{{ route('secteurs.destroy', $secteur->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Pagination Links -->
    <div class="mt-3">
        {{ $secteurs->links() }}
    </div>
@endsection
