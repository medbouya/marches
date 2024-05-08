@extends('layout')

@section('content')
    <h1>Modifier l'autorité contractante</h1>
    <form action="{{ route('autorite-contractantes.update', $autoriteContractante->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $autoriteContractante->name }}" required>
        </div>
        <div class="form-group">
            <label for="address">Adresse</label>
            <input type="text" name="address" id="address" class="form-control" value="{{ $autoriteContractante->address }}">
        </div>
        <div class="form-group">
            <label for="contact_person">Personne de contact</label>
            <input type="text" name="contact_person" id="contact_person" class="form-control" value="{{ $autoriteContractante->contact_person }}">
        </div>
        <div class="form-group">
            <label for="is_exempted">Dérogation</label>
            <select name="is_exempted" id="is_exempted" class="form-control">
                <option value="0" {{ $autoriteContractante->is_exempted == 0 ? 'selected' : '' }}>Non</option>
                <option value="1" {{ $autoriteContractante->is_exempted == 1 ? 'selected' : '' }}>Oui</option>
            </select>
        </div>
        <div class="form-group" style="display: {{ $autoriteContractante->is_exempted ? '' : 'none' }};">
            <label for="market_types">Type(s) de marché</label>
            <select name="market_types[]" id="market_types" class="form-control" multiple>
                @foreach ($marketTypes as $type)
                    <option value="{{ $type->id }}" {{ $autoriteContractante->marketTypes->contains($type->id) ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" id="secteurs_container" style="display: {{ $autoriteContractante->is_exempted ? '' : 'none' }};">
            <label for="secteurs">Secteur</label>
            <select name="secteurs[]" id="secteurs" class="form-control" multiple required>
                @foreach ($secteurs as $secteur)
                    <option value="{{ $secteur->id }}" {{ $autoriteContractante->secteurs->contains($secteur->id) ? 'selected' : '' }}>{{ $secteur->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
@endsection

@section('js')
<script>
$(document).ready(function() {
    console.log('ready');
    var $isExemptedSelect = $('#is_exempted');
    var $marketTypesSelect = $('#market_types');
    var $secteursContainer = $('#secteurs_container');

    console.log($isExemptedSelect.val());
    console.log($marketTypesSelect.val());
    console.log($secteursContainer.val());

    function toggleMarketTypesRequiredAndVisibility() {
        console.log('change');
        if ($isExemptedSelect.val() === '1') { // If 'Oui'
            $marketTypesSelect.prop('required', true);
            $marketTypesSelect.parent().show(); // Ensure you're targeting the parent element if the select is wrapped
            $secteursContainer.show();
        } else {
            $marketTypesSelect.prop('required', false);
            $marketTypesSelect.parent().hide(); // Ensure you're targeting the parent element if the select is wrapped
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
