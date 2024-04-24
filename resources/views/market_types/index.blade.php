<!-- resources/views/market_types/index.blade.php -->

@extends('layout')

@section('content')
    <h1>Types de marchés</h1>
    <a href="{{ route('market-types.create') }}" class="btn btn-primary">Nouveau type de marchés</a>

    <table class="table table-sm table-responsive table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Seuil minimum</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($marketTypes as $marketType)
                <tr>
                    <td>{{ $marketType->id }}</td>
                    <td>{{ ucfirst($marketType->name) }}</td>
                    <td>{{ number_format($marketType->minimum_threshold, 2, '.', ',') }} MRU</td>
                    <td>
                        <a href="{{ route('market-types.edit', $marketType->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('market-types.destroy', $marketType->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Etes-vous sûr?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
