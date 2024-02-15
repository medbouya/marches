<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Marchés à auditer</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Markets Report</h2>
    <table>
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Année</th>
                <th>Objet</th>
                <th>CPMP</th>
                <th>Autorité contractante</th>
                <th>Type de marché</th>
                <th>Mode de passation</th>
                <th>Secteur</th>
                <th>Montant</th>
                <th>Attributaire</th>
                <th>Date de signature</th>
                <th>Date de notificaiton</th>
                <th>Date de publication</th>
                <th>Délai d'exécution</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($filteredMarkets as $market)
                <tr>
                    <td>{{ $market->numero }}</td>
                    <td>{{ $market->year }}</td>
                    <td>{{ $market->title }}</td>
                    <td>{{ ucfirst($market->cpmp->name) }}</td>
                    <td>{{ ucfirst($market->autoriteContractante->name) }}</td>
                    <td>{{ ucfirst($market->marketType->name) }}</td>
                    <td>{{ ucfirst($market->modePassation->name) }}</td>
                    <td>{{ ucfirst($market->secteur->name) }}</td>
                    <td>{{ number_format($market->amount, 2, '.', ',') }} MRU</td>
                    <td>{{ ucfirst($market->attributaire->name) }}</td>
                    <td>{{ $market->date_publication }}</td>
                    <td>{{ $market->date_notification }}</td>
                    <td>{{ \Carbon\Carbon::parse($market->date_signature)->translatedFormat('d F Y') }}</td>
                    <td>{{ $market->delai_execution }} jours</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
