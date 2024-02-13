@extends('layout')

@section('content')
<form action="{{ url('/import/markets') }}" method="POST" enctype="multipart/form-data">
    @csrf <!-- CSRF token for security -->
    <div class="form-group">
        <label for="file">Choisir un fichier</label>
        <input class="form-control" type="file" id="file" name="file" required>
    </div>
    <button type="submit">Téléverser</button>
</form>
@endsection
