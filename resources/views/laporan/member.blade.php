@extends('layouts.app')

@section('title', 'Laporan Keanggotaan Member')

@section('content')
<!-- Official Print Header (Only visible on paper print) -->
<div class="d-none d-print-block mb-4 text-center border-bottom pb-3">
    <h3 class="fw-bold mb-0">DOEL GYM</h3>
    <div class="text-muted small">FITNESS & HEALTH CENTER &bull; Laporan Rekapitulasi Member & Status Keanggotaan</div>
    <div class="small text-muted">Dicetak pada: {{ date('d F Y, H:i') }} WIB &bull; Dicetak oleh: {{ Auth::user()->name ?? 'Administrator' }}</div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Laporan Keanggotaan & Keuangan</h3>
        <p class="text-muted mb-0">Laporan status membership, sisa masa aktif, dan rekapitulasi data anggota.</p>
    </div>
    <button onclick="window.print()" class="btn btn-outline-dark">
        <i class="bi bi-printer me-1"></i> Cetak / Ekspor Laporan
    </button>
</div>

<!-- Summary Metrics -->
<div class="row g-3 mb-4">
    <div class="col-md">
        <div class="card stat-card bg-white shadow-sm p-3">
            <span class="text-muted small">Total Anggota</span>
            <h4 class="fw-bold my-1">{{ number_format($totalMembers) }}</h4>
            <span class="text-secondary small">Semua member</span>
        </div>
    </div>
    <div class="col-md">
        <div class="card stat-card bg-white shadow-sm p-3 border-bottom border-success border-3">
            <span class="text-muted small">Member Aktif</span>
            <h4 class="fw-bold text-success my-1">{{ number_format($activeMembers) }}</h4>
            <span class="text-success small fw-medium">{{ $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100) : 0 }}% dari total</span>
        </div>
    </div>
    <div class="col-md">
        <div class="card stat-card bg-white shadow-sm p-3 border-bottom border-danger border-3">
            <span class="text-muted small">Member Expired</span>
            <h4 class="fw-bold text-danger my-1">{{ number_format($expiredMembers) }}</h4>
            <span class="text-danger small fw-medium">Perlu follow up</span>
        </div>
    </div>
    <div class="col-md">
        <div class="card stat-card bg-white shadow-sm p-3 border-bottom border-primary border-3">
            <span class="text-muted small">Total Pemasukan</span>
            <h5 class="fw-bold text-primary my-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h5>
            <span class="text-muted small">Semua transaksi</span>
        </div>
    </div>
    <div class="col-md">
        <div class="card stat-card bg-white shadow-sm p-3 border-bottom border-info border-3">
            <span class="text-muted small">Total Check-in</span>
            <h4 class="fw-bold text-info my-1">{{ number_format($totalAbsensi) }}</h4>
            <span class="text-muted small">Log kehadiran</span>
        </div>
    </div>
</div>

<!-- Filter Box -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('laporan.member') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, kode member, no hp..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Status Membership --</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-filter me-1"></i> Terapkan Filter
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('laporan.member') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Report Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Member</th>
                        <th>Status</th>
                        <th>Paket Terakhir</th>
                        <th>Masa Berlaku</th>
                        <th>Sisa Hari</th>
                        <th>Total Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $m)
                    @php
                        $lastMs = $m->memberships->first();
                        $daysLeft = null;
                        if ($lastMs) {
                            $daysLeft = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($lastMs->tanggal_berakhir), false);
                        }
                    @endphp
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold">{{ $m->nama }}</div>
                            <small class="text-muted font-monospace">{{ $m->kode_member }}</small>
                            <small class="text-secondary d-block"><i class="bi bi-telephone me-1"></i>{{ $m->no_hp }}</small>
                        </td>
                        <td>
                            @if($m->status === 'aktif')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                    <i class="bi bi-check-circle me-1"></i> Aktif
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">
                                    <i class="bi bi-x-circle me-1"></i> Expired
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($lastMs)
                                <div class="fw-semibold text-primary">{{ $lastMs->paket->nama_paket ?? 'Paket' }}</div>
                                <small class="text-muted">Rp {{ number_format($lastMs->paket->harga ?? 0, 0, ',', '.') }}</small>
                            @else
                                <span class="text-muted small">Belum ada paket</span>
                            @endif
                        </td>
                        <td>
                            @if($lastMs)
                                <div>{{ \Carbon\Carbon::parse($lastMs->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($lastMs->tanggal_berakhir)->format('d M Y') }}</div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @if($lastMs)
                                @if($daysLeft > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success">{{ $daysLeft }} Hari Lagi</span>
                                @elseif($daysLeft == 0)
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">Hari Terakhir</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Lewat {{ abs($daysLeft) }} Hari</span>
                                @endif
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                <i class="bi bi-calendar-check me-1 text-primary"></i> {{ $m->absensis->count() }}x Hadir
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-folder-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            Tidak ada data member pada laporan ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($members->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $members->links() }}
    </div>
    @endif
</div>
@endsection
