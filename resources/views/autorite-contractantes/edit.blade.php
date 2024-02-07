@extends('layout')

@section('content')
    <h1>Modifier l'autorité contractante</h1>

    <form action="{{ route('autorite-contractantes.update', $autoriteContractante->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $autoriteContractante->name }}" required>
        </div>
        <div class="form-group">
            <label for="address">Adresse</label>
            <input type="text" name="address" id="address" class="form-control" value="{{ $autoriteContractante->address }}">
        </div>
        <div class="form-group">
            <label for="contact_person">Personne de contact</label>
            <input type="text" name="contact_person" id="contact_person" class="form-control" value="{{ $autoriteContractante->contact_person }}">
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
@endsection
