@extends('layout')

@section('content')
    <h1>Nouvelle autorité contractante</h1>

    <form action="{{ route('autorite-contractantes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="address">Adresse</label>
            <input type="text" name="address" id="address" class="form-control">
        </div>
        <div class="form-group">
            <label for="contact_person">Personne de contact</label>
            <input type="text" name="contact_person" id="contact_person" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
@endsection
