@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Dashboard Gym</h3>
        <p class="text-muted mb-0">Ringkasan aktivitas member, absensi, dan transaksi hari ini.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('absensi.index') }}" class="btn btn-warning text-dark fw-medium">
            <i class="bi bi-qr-code-scan me-1"></i> Scan Absensi
        </a>
        <a href="{{ route('members.create') }}" class="btn btn-primary fw-medium">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Member
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-white shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Total Member</span>
                    <h3 class="fw-bold my-1">{{ number_format($totalMember) }}</h3>
                    <span class="text-muted small">Terdaftar di sistem</span>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-white shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Member Aktif</span>
                    <h3 class="fw-bold my-1 text-success">{{ number_format($memberAktif) }}</h3>
                    <span class="badge bg-success bg-opacity-10 text-success fw-medium">Bisa Check-in</span>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-white shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Member Expired</span>
                    <h3 class="fw-bold my-1 text-danger">{{ number_format($memberExpired) }}</h3>
                    <span class="badge bg-danger bg-opacity-10 text-danger fw-medium">Perlu Perpanjangan</span>
                </div>
                <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-white shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Absensi Hari Ini</span>
                    <h3 class="fw-bold my-1 text-info">{{ number_format($absensiHariIni) }}</h3>
                    <span class="text-muted small">{{ date('d M Y') }}</span>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card bg-white shadow-sm p-3 border-start border-primary border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Total Pendapatan (All-Time)</span>
                    <h4 class="fw-bold text-primary mb-0 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                    <span class="text-muted small">{{ $totalTransaksi }} Total Transaksi</span>
                </div>
                <i class="bi bi-wallet2 fs-2 text-primary opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-white shadow-sm p-3 border-start border-success border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Pendapatan Bulan Ini ({{ date('F Y') }})</span>
                    <h4 class="fw-bold text-success mb-0 mt-1">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h4>
                    <span class="text-muted small">Pemasukan bulan berjalan</span>
                </div>
                <i class="bi bi-cash-stack fs-2 text-success opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row g-4">
    <!-- Absensi Terbaru Hari Ini -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span class="fw-semibold"><i class="bi bi-clock-history me-1 text-primary"></i> Absensi Terbaru Hari Ini</span>
                <a href="{{ route('absensi.riwayat') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($absensiTerbaru->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-qr-code fs-3 d-block mb-1 opacity-50"></i>
                        Belum ada member yang check-in hari ini.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Member</th>
                                    <th>Jam Check-in</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($absensiTerbaru as $absen)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $absen->member->kode_member ?? '-' }}</span></td>
                                    <td class="fw-medium">{{ $absen->member->nama ?? 'Unknown' }}</td>
                                    <td><i class="bi bi-clock text-muted me-1"></i>{{ substr($absen->jam, 0, 5) }} WIB</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span class="fw-semibold"><i class="bi bi-credit-card me-1 text-success"></i> Transaksi Membership Terbaru</span>
                <a href="{{ route('transaksi-membership.index') }}" class="btn btn-outline-success btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($transaksiTerbaru->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-receipt fs-3 d-block mb-1 opacity-50"></i>
                        Belum ada riwayat transaksi.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Member</th>
                                    <th>Paket</th>
                                    <th>Total</th>
                                    <th>Metode</th>
                                    <th class="text-end pe-3">Struk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksiTerbaru as $trx)
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $trx->member->nama ?? '-' }}</div>
                                        <small class="text-muted">{{ $trx->tanggal_bayar ? \Carbon\Carbon::parse($trx->tanggal_bayar)->format('d M Y') : '-' }}</small>
                                    </td>
                                    <td><span class="badge bg-info text-dark">{{ $trx->paket->nama_paket ?? '-' }}</span></td>
                                    <td class="fw-semibold text-success">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ strtoupper($trx->metode_pembayaran ?? 'CASH') }}</span></td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('transaksi-membership.struk', $trx->id) }}" target="_blank" class="btn btn-outline-dark btn-sm py-0 px-2" title="Cetak Struk">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
