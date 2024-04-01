@extends('layout')

@section('content')
    <div class="container">
        <h2>CPMPs</h2>
        <a href="{{ route('cpmps.create') }}" class="btn btn-primary mb-2">Nouvelle commission de passation de marchés</a>
        <table class="table table-sm table-responsive table-bordered table-hover">
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
                        <td>{{ ucfirst($cpmp->name) }}</td>
                        <td>{{ $cpmp->description }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton{{ $cpmp->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $cpmp->id }}">
                                    <a class="dropdown-item" href="{{ route('cpmps.show', $cpmp->id) }}">Voir</a>
                                    <a class="dropdown-item" href="{{ route('cpmps.edit', $cpmp->id) }}">Editer</a>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $cpmp->id }}').submit();">
                                        Supprimer
                                    </a>
                                </div>
                            </div>
                            <form id="delete-form-{{ $cpmp->id }}" action="{{ route('cpmps.destroy', $cpmp->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Pagination Links -->
        <div class="mt-3">
            {{ $cpmps->links() }}
        </div>
    </div>
@endsection
