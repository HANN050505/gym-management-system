@extends('layouts.app')

@section('content')

<h2>Edit Paket</h2>

<form
action="{{ route('paket-memberships.update',$paket_membership->id) }}"
method="POST">

@csrf
@method('PUT')

<div class="mb-3">
    <label>Nama Paket</label>

    <input
        type="text"
        name="nama_paket"
        class="form-control"
        value="{{ $paket_membership->nama_paket }}">
</div>

<div class="mb-3">
    <label>Durasi Hari</label>

    <input
        type="number"
        name="durasi_hari"
        class="form-control"
        value="{{ $paket_membership->durasi_hari }}">
</div>

<div class="mb-3">
    <label>Harga</label>

    <input
        type="number"
        name="harga"
        class="form-control"
        value="{{ $paket_membership->harga }}">
</div>

<button class="btn btn-success">
    Update
</button>

</form>

@endsection