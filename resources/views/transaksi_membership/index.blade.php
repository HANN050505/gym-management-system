@extends('layouts.app')

@section('content')

<h2>Pembelian Membership</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<form action="/transaksi-membership" method="POST">

    @csrf

    <div class="mb-3">
        <label>Member</label>

        <select name="member_id" class="form-control">

            @foreach($members as $member)

            <option value="{{ $member->id }}">
                {{ $member->nama }}
            </option>

            @endforeach

        </select>
    </div>

    <div class="mb-3">
        <label>Paket Membership</label>

        <select name="paket_id" class="form-control">

            @foreach($pakets as $paket)

            <option value="{{ $paket->id }}">
                {{ $paket->nama_paket }}
                - Rp {{ number_format($paket->harga) }}
            </option>

            @endforeach

        </select>
    </div>

    <button type="submit" class="btn btn-success">
        Beli Membership
    </button>

</form>

@endsection