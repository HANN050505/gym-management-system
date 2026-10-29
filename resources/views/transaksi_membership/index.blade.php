@extends('layouts.app')

@section('title', 'Transaksi Membership')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Transaksi & Langganan Membership</h3>
        <p class="text-muted mb-0">Kelola pembelian paket baru, perpanjangan keanggotaan, cetak struk kasir, dan riwayat pembayaran.</p>
    </div>
</div>

@if(session('last_transaksi_id'))
<div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 d-flex justify-content-between align-items-center" role="alert">
    <div>
        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
        <strong>Transaksi Berhasil Diproses!</strong> Pembayaran telah dicatat dan membership langsung aktif.
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transaksi-membership.struk', session('last_transaksi_id')) }}" target="_blank" class="btn btn-dark btn-sm fw-semibold">
            <i class="bi bi-printer-fill me-1"></i> Cetak Struk Kasir
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

<div class="row g-4">
    <!-- Form Beli / Perpanjang Membership -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-cart-plus me-1 text-primary"></i> Form Transaksi Baru</span>
            </div>
            <div class="card-body">
                <form action="{{ route('transaksi-membership.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Member <span class="text-danger">*</span></label>
                        <select name="member_id" class="form-select @error('member_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Member --</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}" {{ old('member_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->kode_member }} - {{ $m->nama }} ({{ ucfirst($m->status) }})
                                </option>
                            @endforeach
                        </select>
                        @error('member_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Paket Membership <span class="text-danger">*</span></label>
                        <select name="paket_id" class="form-select @error('paket_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Paket --</option>
                            @foreach($pakets as $p)
                                <option value="{{ $p->id }}" {{ old('paket_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_paket }} ({{ $p->durasi_hari }} Hari) - Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('paket_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Metode Pembayaran <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="metodeCash" value="cash" {{ old('metode_pembayaran', 'cash') === 'cash' ? 'checked' : '' }}>
                                <label class="form-check-label" for="metodeCash">Cash</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="metodeTransfer" value="transfer" {{ old('metode_pembayaran') === 'transfer' ? 'checked' : '' }}>
                                <label class="form-check-label" for="metodeTransfer">Transfer</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="metodeQris" value="qris" {{ old('metode_pembayaran') === 'qris' ? 'checked' : '' }}>
                                <label class="form-check-label" for="metodeQris">QRIS</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Catatan Transaksi</label>
                        <input type="text" name="catatan" class="form-control" placeholder="Contoh: Bayar lunas di kasir..." value="{{ old('catatan') }}">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Proses & Simpan Transaksi
                    </button>
                </form>
            </div>
            <div class="card-footer bg-light small text-muted">
                <i class="bi bi-info-circle me-1"></i> Jika member masih memiliki membership aktif, masa aktif akan diperpanjang secara otomatis.
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <form method="GET" action="{{ route('transaksi-membership.index') }}" class="row g-2 align-items-center">
                    <div class="col-md-7">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari nama member atau kode..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-5 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-dark">Cari</button>
                        @if(request('search'))
                            <a href="{{ route('transaksi-membership.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Tanggal</th>
                                <th>Member</th>
                                <th>Paket</th>
                                <th>Total</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Struk / Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $trx)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($trx->tanggal_bayar)->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $trx->created_at->format('H:i') }} WIB</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $trx->member->nama ?? 'Member Dihapus' }}</div>
                                    <small class="text-muted font-monospace">{{ $trx->member->kode_member ?? '-' }}</small>
                                </td>
                                <td>
                                    <div class="fw-medium text-primary">{{ $trx->paket->nama_paket ?? '-' }}</div>
                                    <small class="text-muted">{{ $trx->paket->durasi_hari ?? '-' }} Hari</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">
                                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ strtoupper($trx->metode_pembayaran) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                        {{ ucfirst($trx->status_pembayaran) }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('transaksi-membership.struk', $trx->id) }}" target="_blank" class="btn btn-outline-dark" title="Cetak Struk Pembayaran">
                                            <i class="bi bi-printer me-1"></i> Struk
                                        </a>
                                        @if($trx->member && $trx->member->no_hp)
                                            @php
                                                $waPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $trx->member->no_hp));
                                                $waMsg = "Halo " . $trx->member->nama . ", bukti pembayaran paket " . ($trx->paket->nama_paket ?? 'Gym') . " sebesar Rp " . number_format($trx->total, 0, ',', '.') . " di DOEL GYM telah lunas. Terima kasih!";
                                            @endphp
                                            <a href="https://wa.me/{{ $waPhone }}?text={{ urlencode($waMsg) }}" target="_blank" class="btn btn-outline-success" title="Kirim Konfirmasi WA">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-receipt fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                    Belum ada catatan transaksi pembayaran.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($transaksis->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $transaksis->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
