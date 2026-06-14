@extends('layouts.app')

@section('content')

<h2>Notifikasi Membership</h2>

<table class="table table-bordered">

    <tr>
        <th>No</th>
        <th>Pesan</th>
        <th>Tanggal</th>
    </tr>

    @foreach($notifikasis as $n)

    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $n->pesan }}</td>
        <td>{{ $n->created_at }}</td>
    </tr>

    @endforeach

</table>

@endsection