@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')
<!-- Official Print Header (Only visible on paper print) -->
<div class="d-none d-print-block mb-4 text-center border-bottom pb-3">
    <h3 class="fw-bold mb-0">DOEL GYM</h3>
    <div class="text-muted small">FITNESS & HEALTH CENTER &bull; Laporan Riwayat Kehadiran & Absensi Member</div>
    <div class="small text-muted">Dicetak pada: {{ date('d F Y, H:i') }} WIB &bull; Dicetak oleh: {{ Auth::user()->name ?? 'Administrator' }}</div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Riwayat Absensi Member</h3>
        <p class="text-muted mb-0">Log seluruh kehadiran anggota di fasilitas gym.</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-dark">
            <i class="bi bi-printer me-1"></i> Cetak Log
        </button>
        <a href="{{ route('absensi.index') }}" class="btn btn-primary fw-medium">
            <i class="bi bi-qr-code-scan me-1"></i> Buka Scanner
        </a>
    </div>
</div>

<!-- Filter Box -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('absensi.riwayat') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Cari Member</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nama atau kode member..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100">
                    <i class="bi bi-filter me-1"></i> Filter
                </button>
                @if(request('search') || request('start_date') || request('end_date'))
                    <a href="{{ route('absensi.riwayat') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-list-check me-1 text-primary"></i> Daftar Log Kehadiran</span>
        <div class="d-flex gap-2">
            <span class="badge bg-light text-dark border">Total Log: <strong>{{ number_format($totalCheckins) }}</strong></span>
            <span class="badge bg-success bg-opacity-10 text-success border border-success">Hari Ini: <strong>{{ number_format($todayCheckins) }}</strong></span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Kode Member</th>
                        <th>Nama Member</th>
                        <th>Kontak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $item)
                    <tr>
                        <td class="ps-3 fw-semibold">
                            <i class="bi bi-calendar-event me-1 text-muted"></i>
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border font-monospace fs-6">
                                <i class="bi bi-clock me-1"></i>{{ substr($item->jam, 0, 5) }} WIB
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary font-monospace">{{ $item->member->kode_member ?? '-' }}</span>
                        </td>
                        <td class="fw-bold">
                            {{ $item->member->nama ?? 'Member Telah Dihapus' }}
                        </td>
                        <td>
                            <small class="text-muted">{{ $item->member->no_hp ?? '-' }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            Tidak ada data absensi yang sesuai kriteria pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($absensis->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $absensis->links() }}
    </div>
    @endif
</div>
@endsection
