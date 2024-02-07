<div class="form-group">
    <label for="numero">Numéro:</label>
    <input type="text" name="numero" class="form-control" value="{{ old('numero', $market->numero ?? '') }}" required>
</div>

<div class="form-group">
    <label for="title">Objet:</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $market->title ?? '') }}" required>
</div>

<div class="form-group">
    <label for="amount">Montant:</label>
    <input type="number" name="amount" class="form-control" value="{{ old('amount', $market->amount ?? '') }}" required>
</div>

<div class="form-group">
    <label for="authority_contracting">Autorité contractante:</label>
    <select name="authority_contracting" class="form-control" required>
        {{-- Include options based on your market types --}}
        @foreach ($autoriteContractantes as $autoriteContractante)
            <option value="{{ $autoriteContractante->id }}" @if ($autoriteContractante->id === old('authority_contracting', $market->authority_contracting ?? '')) selected @endif>{{ $autoriteContractante->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="passation_mode">Mode de passation:</label>
    <select name="passation_mode" class="form-control" required>
        {{-- Include options based on your market types --}}
        @foreach ($modePassations as $modePassation)
            <option value="{{ $modePassation->id }}" @if ($modePassation->id === old('passation_mode', $market->passation_mode ?? '')) selected @endif>{{ $modePassation->name }}</option>
        @endforeach
    </select>
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

<div class="form-group">
    <label for="cpmp_id">CPMP:</label>
    <select name="cpmp_id" class="form-control" required>
        {{-- Include options based on your market types --}}
        @foreach ($cpmps as $cpmp)
            <option value="{{ $cpmp->id }}" @if ($cpmp->id === old('cpmp_id', $market->cpmp_id ?? '')) selected @endif>{{ $cpmp->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="secteur_id">Secteur:</label>
    <select name="secteur_id" class="form-control" required>
        {{-- Include options based on your market types --}}
        @foreach ($secteurs as $secteur)
            <option value="{{ $secteur->id }}" @if ($secteur->id === old('secteur_id', $market->secteur_id ?? '')) selected @endif>{{ $secteur->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="attributaire_id">Attributaire:</label>
    <select name="attributaire_id" class="form-control" required>
        {{-- Include options based on your market types --}}
        @foreach ($attributaires as $attributaire)
            <option value="{{ $attributaire->id }}" @if ($attributaire->id === old('attributaire_id', $market->attributaire_id ?? '')) selected @endif>{{ $attributaire->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="financement">Financement:</label>
    <input type="text" name="financement" class="form-control" value="{{ old('financement', $market->financement ?? '') }}" required>
</div>

<div class="form-group">
    <label for="date_signature">Date de signature:</label>
    <input type="date" name="date_signature" class="form-control" value="{{ old('date_signature', $market->date_signature ?? '') }}" required>
</div>

<div class="form-group">
    <label for="date_notification">Date de notification:</label>
    <input type="date" name="date_notification" class="form-control" value="{{ old('date_notification', $market->date_notification ?? '') }}" required>
</div>

<div class="form-group">
    <label for="date_publication">Date de publication:</label>
    <input type="date" name="date_publication" class="form-control" value="{{ old('date_publication', $market->date_publication ?? '') }}" required>
</div>


<div class="form-group">
    <label for="delai_execution">Délai d'exécution:</label>
    <input type="number" name="delai_execution" class="form-control" value="{{ old('delai_execution', $market->delai_execution ?? '') }}" required>
</div>