@extends('layouts.app')

@section('content')

<h2>Data Paket Membership</h2>

<a href="{{ route('paket-memberships.create') }}"
   class="btn btn-primary mb-3">
   Tambah Paket
</a>

<table class="table table-bordered">

<tr>
    <th>Nama Paket</th>
    <th>Durasi</th>
    <th>Harga</th>
    <th>Aksi</th>
</tr>

@foreach($pakets as $paket)

<tr>

    <td>{{ $paket->nama_paket }}</td>

    <td>{{ $paket->durasi_hari }} Hari</td>

    <td>Rp {{ number_format($paket->harga) }}</td>

    <td>

        <a href="{{ route('paket-memberships.edit',$paket->id) }}"
           class="btn btn-warning btn-sm">
           Edit
        </a>

        <form
            action="{{ route('paket-memberships.destroy',$paket->id) }}"
            method="POST"
            style="display:inline">

            @csrf
            @method('DELETE')

            <button class="btn btn-danger btn-sm">
                Hapus
            </button>

        </form>

    </td>

</tr>

@endforeach

</table>

@endsection