<!-- resources/views/market_types/edit.blade.php -->

@extends('layout')

@section('content')
    <h1>Modifier un type de marchés</h1>

    <form action="{{ route('market-types.update', $marketType->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $marketType->name }}" required>
            <label for="minimum_threshold">Seuil minimum</label>
            <input type="number" name="minimum_threshold" id="minimum_threshold" class="form-control" value="{{ $marketType->minimum_threshold }}" required>
        </div>

        <!-- Add more fields as needed -->

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
@endsection
