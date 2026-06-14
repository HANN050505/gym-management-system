@extends('layouts.app')

@section('content')

<h2>Riwayat Absensi</h2>

<table class="table table-bordered">

    <thead>

        <tr>
            <th>No</th>
            <th>Nama Member</th>
            <th>Tanggal</th>
            <th>Jam</th>
        </tr>

    </thead>

    <tbody>

        @foreach($absensis as $absensi)

        <tr>

            <td>
                {{ $loop->iteration }}
            </td>

            <td>
                {{ $absensi->member->nama }}
            </td>

            <td>
                {{ $absensi->tanggal }}
            </td>

            <td>
                {{ $absensi->jam }}
            </td>

        </tr>

        @endforeach

    </tbody>

</table>

@endsection