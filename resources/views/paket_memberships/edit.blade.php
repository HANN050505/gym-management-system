@extends('layouts.app')

@section('title', 'Edit Paket Membership')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-0">Edit Paket Membership</h3>
                <small class="text-muted">Perbarui data paket {{ $paket_membership->nama_paket }}</small>
            </div>
            <a href="{{ route('paket-memberships.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('paket-memberships.update', $paket_membership->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" name="nama_paket" class="form-control @error('nama_paket') is-invalid @enderror" value="{{ old('nama_paket', $paket_membership->nama_paket) }}" required>
                        @error('nama_paket')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Durasi (Hari) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="durasi_hari" min="1" class="form-control @error('durasi_hari') is-invalid @enderror" value="{{ old('durasi_hari', $paket_membership->durasi_hari) }}" required>
                                <span class="input-group-text">Hari</span>
                            </div>
                            @error('durasi_hari')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga / Tarif (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga" min="0" step="1000" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga', (int)$paket_membership->harga) }}" required>
                            </div>
                            @error('harga')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Paket <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="aktif" {{ old('status', $paket_membership->status) === 'aktif' ? 'selected' : '' }}>Aktif (Bisa dipilih saat transaksi)</option>
                            <option value="nonaktif" {{ old('status', $paket_membership->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Deskripsi / Fasilitas Tambahan</label>
                        <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $paket_membership->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('paket-memberships.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Perbarui Paket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
