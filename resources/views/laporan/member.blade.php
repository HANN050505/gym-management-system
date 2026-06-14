@extends('layouts.app')

@section('content')

<h2>Laporan Membership</h2>

<table class="table table-bordered">

<tr>
    <th>Nama</th>
    <th>Paket</th>
    <th>Harga</th>
    <th>Tanggal Mulai</th>
    <th>Tanggal Berakhir</th>
    <th>Status</th>
</tr>

@foreach($laporan as $item)

<tr>

    <td>{{ $item->nama }}</td>

    <td>{{ $item->nama_paket }}</td>

    <td>Rp {{ number_format($item->harga) }}</td>

    <td>{{ $item->tanggal_mulai }}</td>

    <td>{{ $item->tanggal_berakhir }}</td>

    <td>{{ $item->status }}</td>

</tr>

@endforeach

</table>

@endsection