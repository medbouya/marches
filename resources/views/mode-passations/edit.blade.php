@extends('layout')

@section('content')
    <h1>Modifier le type de marchés</h1>

    <form action="{{ route('mode-passations.update', $modePassation->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $modePassation->name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $modePassation->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
@endsection
