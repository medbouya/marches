@extends('layout')

@section('content')
    <div class="container">
        <h2>Paramtètres d'audit</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('audit-settings.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="year">Année:</label>
                <input type="text" name="year" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="minimum_amount_to_audit">Seuil minimum:</label>
                <input type="text" name="minimum_amount_to_audit" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="market_type_id">Type de marchés:</label>
                <select name="market_type_id[]" class="form-control" required multiple>
                    {{-- Include options based on your market types --}}
                    @foreach ($marketTypes as $marketType)
                        <option value="{{ $marketType->id }}">{{ $marketType->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
@endsection
