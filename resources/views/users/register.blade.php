@extends('layout')

@section('content')
<div class="container">
    <h2>Nouvel utilisateur</h2>
    <div class="col-6">
        <form method="POST" action="{{ route('users.register.store') }}">
            @csrf
            <!-- Name -->
            <div class="form-group">
                <label for="name">Nom</label>
                <input id="name" type="text" name="name" class="form-control" required autofocus>
            </div>

            <!-- Email Address -->
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" class="form-control" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" class="form-control" required>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
            </div>

            <div>
                <button type="submit" class="btn btn-success btn-sm">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
