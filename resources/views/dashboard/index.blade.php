@extends('layout')

@section('header')
    <h2>Tableau de bord</h2>
@endsection

@section('content')
    <div class="container">
        <p><b><i>Année d'audit: {{ $auditSetting->year }}</i></b></p>
        <p><b><i>Seuil d'audit: {{ number_format($minimumAmount, 2, '.', ',') }} MRU</i></b></p>
        <p><b><i>Types de marchés à auditer</i></b></p>
        <ul>
            @forelse ($marketTypes as $marketType)
                <li><i>{{ $marketType->name }}</i></li>
            @empty
                <li>Pas de type de marchés spécifié dans les paramètres.</li>
            @endforelse
        </ul>

        <h3>Liste des marchés à auditer</h3>
        <ul>
            @forelse ($markets as $market)
                <table class="table table-bordered">
                    <thead>
                        <th>Marché</th>
                        <th>Montant</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $market->title }}</td>
                            <td>{{ number_format($market->amount, 2, '.', ',') }} MRU</td>
                        </tr>
                    </tbody>
                </table>
            @empty
                <li>Pas de marchés entrant les critères des paramètres.</li>
            @endforelse
        </ul>
    </div>
@endsection
