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


        <table class="table">
            <thead>
                <tr>
                    <th style="width=3rem;">Objet</th>
                    <th>Année</th>
                    <th>Montant</th>
                    <th>Authorité contractante</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($filteredMarkets as $market)
                    <tr>
                        <td>{{ ucfirst($market->title) }}</td>
                        <td>{{ $market->year }}</td>
                        <td>{{ number_format($market->amount, 2, '.', ',') }} MRU</td>
                        <td>{{ ucfirst($market->autoriteContractante->name) }}</td>
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
            {{ $filteredMarkets->links() }}
        </div>
    </div>
@endsection
