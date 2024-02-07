@extends('layout')

@section('content')
    <div class="container">
        <h2>Détails du marché</h2>
        <ul class="list-group">
            <li class="list-group-item"><i>Année</i> : <b>{{ $market->year }}</b></li>
            <li class="list-group-item"><i>Objet</i> : <b>{{ $market->title }}</b></li>
            <li class="list-group-item"><i>CPMP</i> : <b>{{ $market->cpmp->name }}</b></li>
            <li class="list-group-item"><i>Autorité contractante</i> : <b>{{ $market->autoriteContractante->name }}</b></li>
            <li class="list-group-item"><i>Type de marché</i> : <b>{{ $market->marketType->name }}</b></li>
            <li class="list-group-item"><i>Mode de passation</i> : <b>{{ $market->modePassation->name }}</b></li>
            <li class="list-group-item"><i>Secteur</i> : <b>{{ $market->secteur->name }}</b></li>
            <li class="list-group-item"><i>Montant</i> : <b>{{ number_format($market->amount, 2, '.', ',') }} MRU</b></li>
            <li class="list-group-item"><i>Attributaire</i> : <b>{{ $market->attributaire->name }}</b></li>
        </ul>
        <a href="{{ route('markets.index') }}" class="btn btn-primary mt-3">Retour</a>
    </div>
@endsection
