@extends('layout')

@section('content')
    <h2>Ajouter un groupe</h2>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif
    <div class="col-6">
        <form method="POST" action="{{ route('roles.store') }}">
            @csrf
            <div class="form-group">
                <label for="name">Nom du groupe:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success btn-sm">Enregistrer</button>
        </form>
    </div>
@endsection
