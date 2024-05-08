@extends('layout')

@section('content')
    <h1>Autorités contractantes</h1>
    <a href="{{ route('autorite-contractantes.create') }}" class="btn btn-primary mb-2">
        Nouvelle autorité contractante
    </a>

    <table class="table table-sm table-responsive table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Adresse</th>
                <th>Personne de contact</th>
                <th>Dérogation</th>
                <th>Type de marché</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($autoriteContractantes as $autoriteContractante)
                <tr>
                    <td>{{ $autoriteContractante->id }}</td>
                    <td>{{ ucfirst($autoriteContractante->name) }}</td>
                    <td>{{ $autoriteContractante->address }}</td>
                    <td>{{ $autoriteContractante->contact_person }}</td>
                    <td>
                        @if ($autoriteContractante->is_exempted)
                            <span class="badge badge-success">Oui</span>
                        @else
                            <span class="badge badge-danger">Non</span>
                        @endif
                    </td>
                    <td>
                        @forelse ($autoriteContractante->marketTypes as $marketType)
                            <span class="badge badge-info">{{ $marketType->name }}</span>
                        @empty
                            <span class="badge badge-primary">Aucun</span>
                        @endforelse
                    </td>
                    <td>
                        <a href="{{ route('autorite-contractantes.edit', $autoriteContractante->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('autorite-contractantes.destroy', $autoriteContractante->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Pagination Links -->
    <div class="mt-3">
        {{ $autoriteContractantes->links() }}
    </div>
@endsection
