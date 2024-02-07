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

        <form action="{{ route('audit-settings.storeOrUpdate') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="year">Année:</label>
                <input type="text" name="year" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="minimum_amount_to_audit">Seuil minimum d'audit:</label>
                <input type="text" name="minimum_amount_to_audit" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="threshold_exclusion">Seuil d'exclusion d'audit:</label>
                <input type="text" name="threshold_exclusion" class="form-control" required>
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

            @foreach($modePassations as $modePassation)
                <div class="form-group">
                    <label for="percentage_{{ $modePassation->id }}">Pourcentage des {{ $modePassation->name }}</label>
                    <input type="number" id="percentage_{{ $modePassation->id }}" name="percentages[{{ $modePassation->id }}]" value="{{ $modePassation->percentage }}" step="0.01" min="0" max="100" class="form-control" required>
                    @error('percentages.' . $modePassation->id)
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
@endsection
