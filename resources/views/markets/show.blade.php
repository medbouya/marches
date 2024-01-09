@extends('layout')

@section('content')
    <div class="container">
        <h2>Market Details</h2>

        <div>
            <strong>Title:</strong> {{ $market->title }}
        </div>

        <div>
            <strong>Amount:</strong> {{ $market->amount }}
        </div>

        <div>
            <strong>Authority Contracting:</strong> {{ $market->authority_contracting }}
        </div>

        <div>
            <strong>Passation Mode:</strong> {{ $market->passation_mode }}
        </div>

        <div>
            <strong>Market Type:</strong> {{ $market->marketType->name }}
        </div>

        <a href="{{ route('markets.index') }}" class="btn btn-primary mt-3">Back</a>
    </div>
@endsection
