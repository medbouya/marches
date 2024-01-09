<div class="form-group">
    <label for="title">Titre:</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $market->title ?? '') }}" required>
</div>

<div class="form-group">
    <label for="amount">Montant:</label>
    <input type="number" name="amount" class="form-control" value="{{ old('amount', $market->amount ?? '') }}" required>
</div>

<div class="form-group">
    <label for="authority_contracting">Autorité contractante:</label>
    <input type="text" name="authority_contracting" class="form-control" value="{{ old('authority_contracting', $market->authority_contracting ?? '') }}" required>
</div>

<div class="form-group">
    <label for="passation_mode">Mode de passation:</label>
    <input type="text" name="passation_mode" class="form-control" value="{{ old('passation_mode', $market->passation_mode ?? '') }}" required>
</div>

<div class="form-group">
    <label for="year">Année:</label>
    <input type="text" name="year" class="form-control" value="{{ old('year', $market->year ?? '') }}" required>
</div>

<div class="form-group">
    <label for="market_type_id">Type de marché:</label>
    <select name="market_type_id" class="form-control" required>
        {{-- Include options based on your market types --}}
        @foreach ($marketTypes as $marketType)
            <option value="{{ $marketType->id }}" @if ($marketType->id === old('market_type_id', $market->market_type_id ?? '')) selected @endif>{{ $marketType->name }}</option>
        @endforeach
    </select>
</div>
