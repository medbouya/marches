@extends('layout')

@section('content')
    <div class="container">
        <h2>CPMP Détails</h2>
        <p><strong>Nom:</strong> {{ $cpmp->name }}</p>
        <p><strong>Description:</strong> {{ $cpmp->description }}</p>
        <a href="{{ route('cpmps.index') }}" class="btn btn-primary">Retour</a>
    </div>
@endsection
