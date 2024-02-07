@extends('layout')

@section('content')
    <div class="container">
        <h2>CPMPs</h2>
        <a href="{{ route('cpmps.create') }}" class="btn btn-primary mb-2">Nouvelle commission de passation de marchés</a>
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
                @foreach ($cpmps as $cpmp)
                    <tr>
                        <td>{{ $cpmp->id }}</td>
                        <td>{{ $cpmp->name }}</td>
                        <td>{{ $cpmp->description }}</td>
                        <td>
                            <a href="{{ route('cpmps.show', $cpmp->id) }}" class="btn btn-info btn-sm">Voir</a>
                            <a href="{{ route('cpmps.edit', $cpmp->id) }}" class="btn btn-primary btn-sm">Editer</a>
                            <form action="{{ route('cpmps.destroy', $cpmp->id) }}" method="POST" style="display: inline;">
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
@endsection
