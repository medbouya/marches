@extends('layout')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.min.css">
@endsection

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
                        <table id="eligibleMarketsTable" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
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
                                        <td>
                                            <input 
                                                type="checkbox" 
                                                name="selected_markets[]" 
                                                value="{{ $market->id }}">
                                        </td>
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

        <form 
            id="auditSelectionForm" 
            action="{{ route('market.selections.save') }}" 
            method="POST" 
            style="display: none;">
            @csrf
            @foreach($marketsToAuditIds as $marketId)
                <input type="hidden" name="selectedMarkets[]" value="{{ $marketId }}">
            @endforeach
            <!-- Hidden inputs will be added here dynamically -->
        </form>
        @if($auditStatus !== true)
            <div class="row d-flex justify-content-center m-2">
                <a href="javascript:void(0);" id="validateSelection" class="btn btn-success btn-lg">
                    Valider cette sélection pour l'audit
                </a>
            </div>
        @endif
    </div>
@endsection

@section('js')

<script src="https://cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#eligibleMarketsTable').DataTable({
            "searching": true,
            "paging": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
            }
        });

        $('#validateSelection').on('click', function() {
            // Loop through checkboxes in the accordion to add them as hidden inputs
            $('input[name="selected_markets[]"]:checked').each(function() {
                // Only add if not already included (e.g., paginated markets)
                if ($('#auditSelectionForm input[value="' + $(this).val() + '"]').length === 0) {
                    $('#auditSelectionForm').append(
                        '<input type="hidden" name="selectedMarkets[]" value="' + $(this).val() + '">');
                }
            });

            // Submit the form
            $('#auditSelectionForm').submit();
        });
    });
</script>

@endsection
