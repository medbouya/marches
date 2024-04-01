@extends('layout')

@section('content')
    <div class="container">
        <h2>Marchés publics</h2>

        <a href="{{ route('markets.create') }}" class="btn btn-success m-1">Ajouter</a>

        <table class="table table-sm table-responsive table-bordered table-striped table-hover">
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
                @forelse ($markets as $market)
                    <tr>
                        <td class="text-justify font-italic mr-1">{{ ucfirst($market->title) }}</td>
                        <td>
                            <span class="badge badge-sm badge-primary">
                                {{ $market->year }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-sm badge-success">
                                {{ number_format($market->amount, 2, '.', ',') }} MRU
                            </span>
                        </td>
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
            {{ $markets->links() }}
        </div>
    </div>
@endsection
