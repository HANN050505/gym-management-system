@extends('layouts.app')

@section('content')

<h2>Detail Member</h2>

<div class="card p-3">

    <h4>{{ $member->nama }}</h4>

    <p>
        Kode Member :
        {{ $member->kode_member }}
    </p>

    {!! QrCode::size(250)
        ->generate($member->kode_member) !!}

</div>

@endsection