@extends('layout')

@section('content')
    <div class="container">
        <h2>Editer le marché</h2>

        <form action="{{ route('markets.update', $market->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Include form fields --}}
            @include('markets.form')

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
@endsection
