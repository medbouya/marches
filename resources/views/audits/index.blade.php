@extends('layout')

@section('content')
    <div class="container">
        <h2>Marchés publics sélectionnés pour l'audit</h2>
        @if($audits->isEmpty())
            <!-- Cacher les liens -->
        @else
        <a class="btn btn-success btn-sm mb-3" href="{{ route('audits.audited') }}">
            Auditer toute la sélection
        </a>
        <a class="btn btn-danger btn-sm mb-3" href="{{ route('audits.cancel') }}">
            Annuler l'audit de cette sélection
        </a>
        <a class="btn btn-primary btn-sm mb-3" href="{{ route('audits.export.excel') }}">
            Export <i class="nav-icon fas fa-file-excel"></i>
        </a>
        @endif
        <table class="table table-sm table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width=3rem;">Objet</th>
                    <th>Montant</th>
                    <th>Mode de passation</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($audits as $audit)
                    <tr>
                        <td class="font-italic">{{ ucfirst($audit->market->title) }}</td>
                        <td>
                            <span class="badge badge-sm badge-info">
                                {{ number_format($audit->market->amount, 2, '.', ',') }} MRU
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-primary badge-sm"> 
                                {{ ucfirst($audit->market->modePassation->name) }}
                            </span>
                        </td>
                        <td>@if($audit->audit_status == 0)
                                <span class="badge badge-warning badge-sm"> Non audité</span>
                            @else
                                <span class="badge badge-success badge-sm"> Audité</span>
                            @endif
                        </td>
                        <td>
                            {{-- Add dropdown for edit and delete actions --}}
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="actionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
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
            {{ $audits->links() }}
        </div>
    </div>
@endsection
