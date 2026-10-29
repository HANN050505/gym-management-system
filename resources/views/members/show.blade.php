@extends('layouts.app')

@section('title', 'Detail & Kartu Member - ' . $member->nama)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Profil & Kartu Member</h3>
        <p class="text-muted mb-0">Informasi keanggotaan dan QR Code absensi.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('members.kartu', $member->id) }}" target="_blank" class="btn btn-warning text-dark fw-bold">
            <i class="bi bi-person-badge me-1"></i> Cetak Kartu Member Resmi
        </a>
        <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- ID Card / QR Card -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 text-center p-4 bg-white" id="printableCard">
            <div class="card-header bg-transparent border-0 pb-0">
                <span class="badge bg-dark px-3 py-2 text-uppercase letter-spacing-1">DOEL GYM PASS</span>
            </div>
            <div class="card-body">
                <!-- Member Photo / Avatar -->
                <div class="mx-auto mb-3 rounded-3 overflow-hidden border shadow-sm d-flex align-items-center justify-content-center bg-light" style="width: 120px; height: 140px;">
                    @if($member->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->foto))
                        <img src="{{ asset('storage/' . $member->foto) }}" alt="{{ $member->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div class="text-secondary text-center">
                            <i class="bi bi-person-bounding-box fs-1"></i>
                            <div class="small mt-1 text-muted">Belum ada foto</div>
                        </div>
                    @endif
                </div>

                <h4 class="fw-bold mb-1">{{ $member->nama }}</h4>
                <div class="text-muted font-monospace fw-semibold fs-5 mb-2">{{ $member->kode_member }}</div>

                <div class="mt-2 mb-3">
                    @if($member->status === 'aktif')
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="bi bi-check-circle-fill me-1"></i> STATUS: AKTIF
                        </span>
                    @else
                        <span class="badge bg-danger fs-6 px-3 py-2">
                            <i class="bi bi-x-circle-fill me-1"></i> STATUS: EXPIRED
                        </span>
                    @endif
                </div>

                <!-- Linear Barcode -->
                <div class="p-2 bg-white rounded border my-3">
                    <div class="text-muted small mb-1 fw-semibold">BARCODE SCANNER ID:</div>
                    <div style="max-height: 45px; overflow: hidden;" class="d-flex justify-content-center">
                        {!! \App\Services\BarcodeService::generateSvg($member->kode_member, 40, 2) !!}
                    </div>
                    <div class="font-monospace small fw-bold text-dark mt-1">{{ $member->kode_member }}</div>
                </div>

                <!-- QR Code Absensi -->
                <div class="p-3 bg-light rounded d-inline-block border shadow-sm my-2">
                    {!! QrCode::size(140)->generate($member->kode_member) !!}
                    <div class="small text-muted mt-1">QR Code Absensi</div>
                </div>

                <hr class="my-3">

                <div class="small text-muted text-start">
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <span><strong>No. HP:</strong> {{ $member->no_hp }}</span>
                        @if($member->no_hp)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $member->no_hp)) }}?text=Halo%20{{ urlencode($member->nama) }},%20salam%20hangat%20dari%20DOEL%20GYM!" target="_blank" class="btn btn-sm btn-outline-success py-0 px-2">
                                <i class="bi bi-whatsapp me-1"></i> WA
                            </a>
                        @endif
                    </div>
                    @if($member->email)<div class="mb-1"><strong>Email:</strong> {{ $member->email }}</div>@endif
                    <div class="mb-1"><strong>Alamat:</strong> {{ $member->alamat ?? '-' }}</div>
                    <div class="mb-1"><strong>Terdaftar:</strong> {{ $member->created_at->format('d M Y') }}</div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('members.kartu', $member->id) }}" target="_blank" class="btn btn-dark fw-semibold">
                        <i class="bi bi-person-badge me-1 text-warning"></i> Buka Tampilan Cetak Kartu Member
                    </a>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 text-muted small">
                Tunjukkan Barcode atau QR code ke scanner saat masuk ke DOEL GYM.
            </div>
        </div>
    </div>

    <!-- Details and History -->
    <div class="col-lg-8">
        <!-- Membership Info -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-box-seam me-1 text-primary"></i> Riwayat Paket Membership</span>
                <a href="{{ route('transaksi-membership.index') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Beli / Perpanjang Paket
                </a>
            </div>
            <div class="card-body p-0">
                @if($member->memberships->isEmpty())
                    <div class="text-center py-4 text-muted">
                        Belum pernah membeli paket membership.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Paket</th>
                                    <th>Periode Aktif</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($member->memberships as $ms)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $ms->paket->nama_paket ?? 'Paket' }}</div>
                                        <small class="text-muted">{{ $ms->paket->durasi_hari ?? '-' }} Hari</small>
                                    </td>
                                    <td>
                                        <div>{{ \Carbon\Carbon::parse($ms->tanggal_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($ms->tanggal_berakhir)->format('d M Y') }}</div>
                                        <small class="text-muted">
                                            @php
                                                $days = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($ms->tanggal_berakhir), false);
                                            @endphp
                                            @if($days > 0)
                                                <span class="text-success">Tersisa {{ $days }} hari</span>
                                            @elseif($days == 0)
                                                <span class="text-warning">Berakhir hari ini</span>
                                            @else
                                                <span class="text-danger">Lewat {{ abs($days) }} hari</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if($ms->status === 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Expired</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Check-ins -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-calendar-check me-1 text-success"></i> Riwayat Absensi Terakhir (10 Terkini)</span>
            </div>
            <div class="card-body p-0">
                @if($member->absensis->isEmpty())
                    <div class="text-center py-4 text-muted">
                        Belum ada riwayat absensi untuk member ini.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($member->absensis as $absen)
                                <tr>
                                    <td><i class="bi bi-calendar-event me-1 text-muted"></i>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d M Y') }}</td>
                                    <td><i class="bi bi-clock me-1 text-muted"></i>{{ substr($absen->jam, 0, 5) }} WIB</td>
                                    <td><span class="badge bg-success bg-opacity-10 text-success border border-success">Hadir</span></td>
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
