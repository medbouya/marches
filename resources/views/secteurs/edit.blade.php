@extends('layout')

@section('content')
    <div class="container">
        <h2>Edition du secteur</h2>
        <form action="{{ route('secteurs.update', $secteur->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nom:</label>
                <input type="text" name="name" class="form-control" value="{{ $secteur->name }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" class="form-control">{{ $secteur->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
@endsection
