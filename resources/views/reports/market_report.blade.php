<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport du Marché 2023</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .page-break {
            page-break-after: always;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .cover {
            text-align: center;
            padding: 50px;
        }
        .toc {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Page de couverture -->
    <div class="cover">
        <h1>Rapport du Marché 2023</h1>
        <p>Préparé par : Votre Organisation</p>
    </div>
    <div class="page-break"></div>

    <!-- Table des matières -->
    <div class="toc">
        <h2>Table des Matières</h2>
        <ul>
            <li>1. Vue d'ensemble du marché</li>
            <li>2. Résumé des audits</li>
        </ul>
    </div>
    <div class="page-break"></div>

    <!-- Vue d'ensemble du marché -->
    <h2>1. Vue d'ensemble du marché</h2>
    <table>
        <thead>
            <tr>
                <th>ID du marché</th>
                <th>Type</th>
                <th>Budget</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($markets as $market)
                <tr>
                    <td>{{ $market->id }}</td>
                    <td>{{ $market->marketType->name }}</td>
                    <td>{{ number_format($market->budget, 2) }} MRU</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Résumé des audits -->
    <div class="page-break"></div>
    <h2>2. Résumé des audits</h2>
    <table>
        <thead>
            <tr>
                <th>ID de l'audit</th>
                <th>ID du marché</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($markets as $market)
                @foreach ($market->audits as $audit)
                    <tr>
                        <td>{{ $audit->id }}</td>
                        <td>{{ $audit->market_id }}</td>
                        <td>{{ $audit->status }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

</body>
</html>
