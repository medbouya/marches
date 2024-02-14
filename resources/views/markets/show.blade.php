@extends('layout')

@section('content')
    <div class="container">
        <h2>Détails du marché</h2>
        <ul class="list-group">
            <li class="list-group-item"><i>Année</i> : <b>{{ $market->year }}</b></li>
            <li class="list-group-item"><i>Objet</i> : <b>{{ ucfirst($market->title) }}</b></li>
            <li class="list-group-item"><i>CPMP</i> : <b>{{ ucfirst($market->cpmp->name) }}</b></li>
            <li class="list-group-item"><i>Autorité contractante</i> : <b>{{ ucfirst($market->autoriteContractante->name) }}</b></li>
            <li class="list-group-item"><i>Type de marché</i> : <b>{{ ucfirst($market->marketType->name) }}</b></li>
            <li class="list-group-item"><i>Mode de passation</i> : <b>{{ ucfirst($market->modePassation->name) }}</b></li>
            <li class="list-group-item"><i>Secteur</i> : <b>{{ ucfirst($market->secteur->name) }}</b></li>
            <li class="list-group-item"><i>Montant</i> : <b>{{ number_format($market->amount, 2, '.', ',') }} MRU</b></li>
            <li class="list-group-item"><i>Attributaire</i> : <b>{{ ucfirst($market->attributaire->name) }}</b></li>
            <li class="list-group-item"><i>Date de publication</i> : <b>{{ $market->date_publication }}</b></li>
            <li class="list-group-item"><i>Date de notification</i> : <b>{{ $market->date_notification }}</b></li>
            <li class="list-group-item">
                <i>Date de signature</i> : <b>{{ \Carbon\Carbon::parse($market->date_signature)->translatedFormat('d F Y') }}</b>
            </li>
            <li class="list-group-item"><i>Délai d'exécution</i> : <b>{{ $market->delai_execution }} jours</b></li>
        </ul>
        <a href="{{ route('markets.index') }}" class="btn btn-primary mt-3">Retour</a>
    </div>
@endsection
