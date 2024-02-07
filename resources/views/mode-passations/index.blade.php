@extends('layout')

@section('content')
    <h1>Modes de passation</h1>
    <a href="{{ route('mode-passations.create') }}" class="btn btn-primary">Nouveau mode de passation</a>

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
            @foreach ($modePassations as $modePassation)
                <tr>
                    <td>{{ $modePassation->id }}</td>
                    <td>{{ $modePassation->name }}</td>
                    <td>{{ $modePassation->description }}</td>
                    <td>
                        <a href="{{ route('mode-passations.edit', $modePassation->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('mode-passations.destroy', $modePassation->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
