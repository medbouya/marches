@extends('layout')

@section('content')
    <div class="container">
        <h2>Edition de la commission</h2>
        <form action="{{ route('cpmps.update', $cpmp->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nom:</label>
                <input type="text" name="name" class="form-control" value="{{ $cpmp->name }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" class="form-control">{{ $cpmp->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
@endsection
