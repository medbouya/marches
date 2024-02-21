@extends('layout')

@section('content')
    <div class="container">
        <h2>Marchés publics à auditer</h2>
        <ul class="list-group">
            <li class="list-group-item">
                <i>Marchés dépassant le seuil minimum d'audit:</i> <b>{{ $marketsAboveMinimumCount }}</b>
            </li>
            @foreach($modePassationCounts as $modePassationName => $count)
               <li class="list-group-item"> <i>{{ ucwords($modePassationName) }}:</i> <b>{{ $count }}</b></li>
            @endforeach
        </ul>
        <div class="row justify-content-center">
            <a class="btn btn-primary btn-lg m-2" href={{ route('markets.toAudit') }}>
                Tirage au sort des marchés à auditer <i class="fa fa-random" aria-hidden="true"></i>
            </a>
        </div>
    </div>
@endsection
