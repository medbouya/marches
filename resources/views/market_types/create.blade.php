<!-- resources/views/market_types/create.blade.php -->

@extends('layout')

@section('content')
    <h1>Créer un nouveau type de marchés</h1>

    <form action="{{ route('market-types.store') }}" method="post">
        @csrf
        <div class="form-group col-6">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <!-- Add more fields as needed -->

        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
@endsection
