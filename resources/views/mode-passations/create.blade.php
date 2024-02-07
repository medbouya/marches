@extends('layout')

@section('content')
    <h1>Nouveau type de marchés</h1>

    <form action="{{ route('mode-passations.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
@endsection
