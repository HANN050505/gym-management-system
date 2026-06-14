@extends('layouts.app')

@section('content')

<h1>Dashboard Gym</h1>

<div class="row">

    <div class="col-md-4 mb-3">

        <div class="card">
            <div class="card-body">

                <h5>Total Member</h5>

                <h2>
                    {{ $totalMember }}
                </h2>

            </div>
        </div>

    </div>

    <div class="col-md-4 mb-3">

        <div class="card">
            <div class="card-body">

                <h5>Member Aktif</h5>

                <h2>
                    {{ $memberAktif }}
                </h2>

            </div>
        </div>

    </div>

    <div class="col-md-4 mb-3">

        <div class="card">
            <div class="card-body">

                <h5>Member Expired</h5>

                <h2>
                    {{ $memberExpired }}
                </h2>

            </div>
        </div>

    </div>

    <div class="col-md-6 mb-3">

        <div class="card">
            <div class="card-body">

                <h5>Absensi Hari Ini</h5>

                <h2>
                    {{ $absensiHariIni }}
                </h2>

            </div>
        </div>

    </div>

    <div class="col-md-6 mb-3">

        <div class="card">
            <div class="card-body">

                <h5>Total Transaksi</h5>

                <h2>
                    {{ $totalTransaksi }}
                </h2>

            </div>
        </div>

    </div>

</div>

@endsection