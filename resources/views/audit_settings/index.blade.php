@extends('layout')

@section('content')
    <div class="container">
        <h1>Paramètres d'audit</h1>
        @if ($auditSetting)
            <a href="{{ route('audit-settings.edit', $auditSetting) }}" class="btn btn-primary m-1">Mettre à jour</a>
        @else
            <a href="{{ route('audit-settings.create') }}" class="btn btn-success m-1">Créer</a>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Année</th>
                    <th>Seuil des marchés</th>
                    <th>Type de marchés</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($auditSettings as $auditSetting)
                    <tr>
                        <td>
                            <h3>
                                <span class="badge badge-secondary">{{ $auditSetting->year }}</span>
                            </h3>
                        </td>
                        <td>
                            <h3>
                                <span class="badge badge-success">
                                    {{ number_format($auditSetting->minimum_amount_to_audit, 2, '.', ',') }} MRU
                                </span>
                            </h3>
                        </td>
                        <td>
                            @foreach ($auditSetting->marketTypes as $marketType)
                                <h5>
                                    <span class="badge badge-warning">
                                        {{ $marketType->name }}
                                    </span>
                                </h5> @if (!$loop->last)  @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
