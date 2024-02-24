@extends('layout')

@section('content')
    <div class="container">
        <h2>Marchés publics à auditer</h2>
        <ul class="list-group">
            <li class="list-group-item">
                <i>Marchés dépassant le seuil minimum d'audit:</i> <b>{{ $marketsAboveMinimumCount }}</b>
            </li>
            @foreach($modePassationCounts as $modePassationName => $count)
               <li class="list-group-item"> <i>{{ $modePassationName }}:</i> <b>{{ $count }}</b></li>
            @endforeach
        </ul>
        <a href="{{ route('markets.toAudit', 'excel') }}" class="btn btn-success m-1">
            Excel <i class="nav-icon fas fa-file-excel"></i></a>
        <!-- <a href="{{ route('markets.toAudit', 'pdf') }}" class="btn btn-danger m-1">
            PDF <i class="nav-icon fas fa-file-pdf"></i></a> -->
        
        <p>
            <a 
                class="btn btn-primary" 
                data-toggle="collapse" 
                href="#multiCollapseExample1" 
                role="button" 
                aria-expanded="false" 
                aria-controls="multiCollapseExample1">
                    Marchés éligibles non sélectionnés
            </a>
        </p>
        <div class="row">
            <div class="col">
                <div class="collapse multi-collapse" id="multiCollapseExample1">
                    <div class="card card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width=3rem;">Objet</th>
                                    <th>Année</th>
                                    <th>Montant</th>
                                    <th>Attributaire</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lessImportantMarkets as $market)
                                    <tr>
                                        <td>{{ ucfirst($market->title) }}</td>
                                        <td>{{ $market->year }}</td>
                                        <td>{{ number_format($market->amount, 2, '.', ',') }} MRU</td>
                                        <td>{{ ucfirst($market->attributaire->name) }}</td>
                                        <td>
                                            {{-- Add dropdown for edit and delete actions --}}
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="actionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="actionsDropdown">
                                                    <a class="dropdown-item" href="{{ route('markets.show', $market->id) }}">Détails</a>
                                                    <a class="dropdown-item" href="{{ route('markets.edit', $market->id) }}">Editer</a>
                                                    <form action="{{ route('markets.destroy', $market->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item">Supprimer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">Aucun marché.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $lessImportantMarkets->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <table class="table">
            <thead>
                <tr>
                    <th style="width=3rem;"  scope="col">Objet</th>
                    <th scope="col">Année</th>
                    <th scope="col">Montant</th>
                    <th scope="col">Mode de passation</th>
                    <th scope="col">Authorité contractante</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($paginatedMarkets as $market)
                    <tr>
                        <td>{{ ucfirst($market->title) }}</td>
                        <td><span class="badge badge-secondary">{{ $market->year }}</span></td>
                        <td>{{ number_format($market->amount, 2, '.', ',') }} MRU</td>
                        <td>
                            <span class="badge badge-pill badge-success">
                                {{ ucfirst($market->modePassation->name) }}
                            </span>
                        </td>
                        <td>
                            {{ ucfirst($market->autoriteContractante->name) }}
                        </td>
                        <td>
                            {{-- Add dropdown for edit and delete actions --}}
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="actionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="actionsDropdown">
                                    <a class="dropdown-item" href="{{ route('markets.show', $market->id) }}">Détails</a>
                                    <a class="dropdown-item" href="{{ route('markets.edit', $market->id) }}">Editer</a>
                                    <form action="{{ route('markets.destroy', $market->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Aucun marché.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Pagination Links -->
        <div class="mt-3">
            {{ $paginatedMarkets->links() }}
        </div>
    </div>
@endsection
