@extends('layout')

@section('content')
    <div class="container">
        <h2>Ajouter un marché</h2>

        <form action="{{ route('markets.store') }}" method="POST">
            @csrf

            {{-- Include form fields --}}
            @include('markets.form')

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
@endsection
