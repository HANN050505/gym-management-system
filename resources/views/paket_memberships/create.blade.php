@extends('layouts.app')

@section('content')

<h2>Tambah Paket</h2>

<form action="{{ route('paket-memberships.store') }}"
      method="POST">

@csrf

<div class="mb-3">
    <label>Nama Paket</label>
    <input
        type="text"
        name="nama_paket"
        class="form-control">
</div>

<div class="mb-3">
    <label>Durasi Hari</label>
    <input
        type="number"
        name="durasi_hari"
        class="form-control">
</div>

<div class="mb-3">
    <label>Harga</label>
    <input
        type="number"
        name="harga"
        class="form-control">
</div>

<button class="btn btn-success">
    Simpan
</button>

</form>

@endsection