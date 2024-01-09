@extends('layout')

@section('content')
    <div class="container">
        <h2>Mettre à jour les paramètres</h2>

        <form action="{{ route('audit-settings.update', $auditSetting->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="year">Year:</label>
                <input type="text" name="year" class="form-control" value="{{ $auditSetting->year }}" required>
            </div>

            <div class="form-group">
                <label for="minimum_amount_to_audit">Minimum Amount to Audit:</label>
                <input type="text" name="minimum_amount_to_audit" class="form-control" value="{{ $auditSetting->minimum_amount_to_audit }}" required>
            </div>

            <div class="form-group">
                <label for="market_type_id">Market Type:</label>
                <select name="market_type_id[]" class="form-control" multiple required>
                    {{-- Include options based on your market types --}}
                    @foreach ($marketTypes as $marketType)
                        <option value="{{ $marketType->id }}" 
                            @if (in_array($marketType->id, $auditSetting->marketTypes->pluck('id')->toArray())) 
                                selected 
                            @endif>
                            {{ $marketType->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
@endsection
