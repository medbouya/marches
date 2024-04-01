@extends('layout')

@section('content')
    <div class="container">
        <h2>Attributaires</h2>
        <a href="{{ route('attributaires.create') }}" class="btn btn-primary mb-2">Nouvel attributaire</a>
        <table class="table table-sm table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attributaires as $attributaire)
                    <tr>
                        <td>{{ $attributaire->id }}</td>
                        <td>{{ ucfirst($attributaire->name) }}</td>
                        <td>{{ $attributaire->description }}</td>
                        <td>
                            <a href="{{ route('attributaires.show', $attributaire->id) }}" class="btn btn-info btn-sm">Voir</a>
                            <a href="{{ route('attributaires.edit', $attributaire->id) }}" class="btn btn-primary btn-sm">Editer</a>
                            <form action="{{ route('attributaires.destroy', $attributaire->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Pagination Links -->
        <div class="mt-3">
            {{ $attributaires->links() }}
        </div>
    </div>
@endsection
