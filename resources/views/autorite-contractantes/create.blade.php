@extends('layout')

@section('content')
    <h1>Nouvelle autorité contractante</h1>
    <form action="{{ route('autorite-contractantes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="address">Adresse</label>
            <input type="text" name="address" id="address" class="form-control">
        </div>
        <div class="form-group">
            <label for="contact_person">Personne de contact</label>
            <input type="text" name="contact_person" id="contact_person" class="form-control">
        </div>
        <div class="form-group">
            <label for="is_exempted">Dérogation</label>
            <select name="is_exempted" id="is_exempted" class="form-control">
                <option value="0">Non</option>
                <option value="1">Oui</option>
            </select>
        </div>
        <div class="form-group">
            <label for="market_types">Type(s) de marché</label>
            <select name="market_types[]" id="market_types" class="form-control" multiple>
                @foreach ($marketTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" id="secteurs_container" style="display: none;">
            <label for="secteurs">Secteur</label>
            <select name="secteurs[]" id="secteurs" class="form-control" multiple required>
                @foreach ($secteurs as $secteur)
                    <option value="{{ $secteur->id }}">{{ $secteur->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var $isExemptedSelect = $('#is_exempted');
    var $marketTypesSelect = $('#market_types');
    var $secteursContainer = $('#secteurs_container');
    var $secteursSelect = $('#secteurs');

    function toggleMarketTypesRequiredAndVisibility() {
        if ($isExemptedSelect.val() === '1') { // If 'Oui'
            $marketTypesSelect.prop('required', true);
            $marketTypesSelect.show();
            $secteursContainer.show();
        } else {
            $marketTypesSelect.prop('required', false);
            $marketTypesSelect.hide();
            $secteursContainer.hide();
        }
    }

    // Initial check on page load
    toggleMarketTypesRequiredAndVisibility();

    // Set up event listener for changes
    $isExemptedSelect.change(toggleMarketTypesRequiredAndVisibility);
});
</script>
@endsection
