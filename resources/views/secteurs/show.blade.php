@extends('layout')

@section('content')
    <div class="container">
        <h2>Secteur Détails</h2>
        <p><strong>Nom:</strong> {{ $secteur->name }}</p>
        <p><strong>Description:</strong> {{ $secteur->description }}</p>
        <a href="{{ route('secteurs.index') }}" class="btn btn-primary">Retour</a>
    </div>
@endsection
