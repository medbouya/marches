@extends('layout')

@section('content')
    <div class="container">
        <h2>Attributaire Détails</h2>
        <p><strong>Nom:</strong> {{ $attributaire->name }}</p>
        <p><strong>Description:</strong> {{ $attributaire->description }}</p>
        <a href="{{ route('attributaires.index') }}" class="btn btn-primary">Retour</a>
    </div>
@endsection
